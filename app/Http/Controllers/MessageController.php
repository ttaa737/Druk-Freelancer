<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Job;
use App\Models\Message;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List all conversations for the authenticated user.
     */
    public function index()
    {
        $userId = Auth::id();

        $conversations = Conversation::where('poster_id', $userId)
            ->orWhere('freelancer_id', $userId)
            ->with(['poster.profile', 'freelancer.profile', 'job', 'latestMessage'])
            ->withCount(['messages as unread_count' => function ($q) use ($userId) {
                $q->where('sender_id', '!=', $userId)->whereNull('read_at');
            }])
            ->latest('updated_at')
            ->paginate(20);

        return view('messages.index', compact('conversations'));
    }

    /**
     * Show or start a conversation between two parties about a job.
     */
    public function show(Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $messages = $conversation->messages()
            ->with('sender.profile')
            ->oldest()
            ->get();

        // Mark all unread messages from the other person as read
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $conversation->load(['poster.profile', 'freelancer.profile', 'job']);

        // Load all conversations for the sidebar
        $userId = Auth::id();
        $conversations = Conversation::where('poster_id', $userId)
            ->orWhere('freelancer_id', $userId)
            ->with(['poster.profile', 'freelancer.profile', 'job', 'latestMessage'])
            ->latest('updated_at')
            ->get();

        return view('messages.show', compact('conversation', 'messages', 'conversations'));
    }

    /**
     * Start or retrieve a conversation with another user about a job.
     */
    public function start(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'job_id'       => 'nullable|exists:jobs,id',
        ]);

        $user = Auth::user();
        $recipient = User::findOrFail($request->recipient_id);
        $job = $request->job_id ? Job::findOrFail($request->job_id) : null;

        // Determine poster vs freelancer
        if ($user->isJobPoster() && $recipient->isFreelancer()) {
            $posterId     = $user->id;
            $freelancerId = $recipient->id;
        } elseif ($user->isFreelancer() && $recipient->isJobPoster()) {
            $posterId     = $recipient->id;
            $freelancerId = $user->id;
        } else {
            return back()->with('error', 'Cannot start a conversation between users of the same role.');
        }

        $conversation = Conversation::firstOrCreate(
            [
                'poster_id'    => $posterId,
                'freelancer_id' => $freelancerId,
                'job_id'       => $job?->id,
            ]
        );

        return redirect()->route('messages.show', $conversation);
    }

    /**
     * Send a message in a conversation.
     */
    public function send(Request $request, Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $request->validate([
            'body'        => 'required_without:attachment|nullable|string|max:5000',
            'attachment'  => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,zip',
        ]);

        $attachmentPath = null;
        $attachmentName = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('message-attachments', 'public');
            $attachmentName = $request->file('attachment')->getClientOriginalName();
        }

        $message = $conversation->messages()->create([
            'sender_id'       => Auth::id(),
            'body'            => $request->body,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);

        $conversation->touch(); // bump updated_at for ordering

        // Notify the other party
        $otherId = $conversation->poster_id === Auth::id()
            ? $conversation->freelancer_id
            : $conversation->poster_id;

        $otherUser = User::find($otherId);
        if ($otherUser) {
            NotificationService::newMessage($otherUser, $message);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => $message->load('sender.profile')]);
        }

        return back();
    }

    /**
     * Mark a specific message as read.
     */
    public function markRead(Message $message)
    {
        if ($message->sender_id !== Auth::id()) {
            $message->update(['read_at' => now()]);
        }

        return response()->json(['ok' => true]);
    }

    /** Fetch new messages since a given message ID (polling fallback). */
    public function poll(Conversation $conversation, int $lastId = 0)
    {
        $this->authorizeConversation($conversation);

        $messages = $conversation->messages()
            ->where('id', '>', $lastId)
            ->with('sender.profile')
            ->get();

        $messages->where('sender_id', '!=', Auth::id())->each->markAsRead();

        return response()->json(['messages' => $messages]);
    }

    // ─── Private ─────────────────────────────────────────────────────────────

    private function authorizeConversation(Conversation $conversation): void
    {
        $userId = Auth::id();
        abort_if(
            $conversation->poster_id !== $userId && $conversation->freelancer_id !== $userId,
            403
        );
    }
}

