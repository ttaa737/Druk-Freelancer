<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Transaction::with('user.profile');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('transaction_ref', 'like', "%{$q}%")
                   ->orWhereHas('user', fn($uq) => $uq->where('email', 'like', "%{$q}%"));
            });
        }
        if ($request->filled('from')) {
            $query->where('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('created_at', '<=', $request->to . ' 23:59:59');
        }

        $transactions = $query->latest()->paginate(30)->withQueryString();

        // Summary stats
        $summary = [
            'total_revenue' => Transaction::where('type', 'platform_fee')
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_withdrawals' => Transaction::where('type', 'withdrawal')
                ->where('status', 'pending')
                ->count(),
            'total_deposits' => Transaction::where('type', 'deposit')
                ->where('status', 'completed')
                ->sum('amount'),
            'total_withdrawn' => Transaction::where('type', 'withdrawal')
                ->where('status', 'completed')
                ->sum('amount'),
        ];

        return view('admin.transactions.index', compact('transactions', 'summary'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('user.profile');

        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Manually approve a pending withdrawal.
     */
    public function approveWithdrawal(Transaction $transaction)
    {
        abort_unless($transaction->type === 'withdrawal' && $transaction->status === 'pending', 403);

        $transaction->update([
            'status'       => 'completed',
            'completed_at' => now(),
            'processed_by' => Auth::id(),
        ]);

        AuditLogService::log('withdrawal.approved', $transaction, userId: Auth::id());

        return back()->with('success', 'Withdrawal approved and marked as completed.');
    }

    /**
     * Reject a pending withdrawal (refund to available balance).
     */
    public function rejectWithdrawal(Request $request, Transaction $transaction)
    {
        abort_unless($transaction->type === 'withdrawal' && $transaction->status === 'pending', 403);
        $request->validate(['reason' => 'required|string|max:500']);

        DB::transaction(function () use ($request, $transaction) {
            $transaction->update([
                'status'       => 'failed',
                'notes'        => $request->reason,
                'processed_by' => Auth::id(),
            ]);

            // Refund the amount back to available balance
            $transaction->user->wallet()->increment('available_balance', $transaction->amount);

            AuditLogService::log('withdrawal.rejected', $transaction, notes: $request->reason);
        });

        return back()->with('success', 'Withdrawal rejected. Amount refunded to user wallet.');
    }
}

