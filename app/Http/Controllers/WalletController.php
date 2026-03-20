<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\AuditLogService;
use App\Services\OTPService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct(
        private PaymentService $payment,
        private OTPService $otp
    ) {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function index()
    {
        $user = Auth::user();
        $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);
        $paymentMethods = PaymentMethod::where('user_id', $user->id)->get();

        $transactions = Transaction::where('user_id', $user->id)
                                   ->latest()
                                   ->paginate(20);

        $providers = PaymentService::PROVIDERS;

        return view('wallet.index', compact('wallet', 'paymentMethods', 'transactions', 'providers'));
    }

    /**
     * Show deposit form.
     */
    public function showDeposit()
    {
        $providers = PaymentService::PROVIDERS;
        return view('wallet.deposit', compact('providers'));
    }

    /**
     * Process a wallet deposit.
     */
    public function deposit(Request $request)
    {
        $request->validate([
            'provider'     => 'required|in:' . implode(',', array_keys(PaymentService::PROVIDERS)),
            'amount'       => 'required|numeric|min:100|max:1000000',
            'provider_ref' => 'required|string|max:100',
        ]);

        try {
            $transaction = $this->payment->deposit(
                Auth::user(),
                $request->amount,
                $request->provider,
                $request->provider_ref
            );

            return redirect()->route('wallet.index')
                             ->with('success', "Nu. " . number_format($request->amount, 2) . " deposited successfully!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show withdrawal form.
     */
    public function showWithdraw()
    {
        $paymentMethods = PaymentMethod::where('user_id', Auth::id())->get();
        $providers = PaymentService::PROVIDERS;
        $wallet = Auth::user()->wallet;

        return view('wallet.withdraw', compact('paymentMethods', 'providers', 'wallet'));
    }

    /**
     * Send OTP before withdrawal.
     */
    public function sendWithdrawOTP(Request $request)
    {
        $user = Auth::user();
        
        // Validate the withdrawal details
        $request->validate([
            'amount' => 'required|numeric|min:500',
            'provider' => 'required|in:' . implode(',', array_keys(PaymentService::PROVIDERS)),
            'account_number' => 'required|string|max:50',
        ]);
        
        $this->otp->sendEmailOTP($user, 'withdrawal');

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'OTP sent to your email']);
        }

        return back()->with('success', 'OTP sent to your email. Valid for 10 minutes.');
    }

    /**
     * Process a withdrawal request.
     */
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:500',
            'provider'       => 'required|in:' . implode(',', array_keys(PaymentService::PROVIDERS)),
            'account_number' => 'required|string|max:50',
            'otp'            => 'required|string|size:6',
        ]);

        // Verify OTP
        if (!$this->otp->verify(Auth::user()->email, 'withdrawal', $request->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please request a new one.']);
        }

        try {
            $transaction = $this->payment->withdraw(
                Auth::user(),
                $request->amount,
                $request->provider,
                $request->account_number
            );

            return redirect()->route('wallet.index')
                             ->with('success', "Withdrawal of Nu. " . number_format($request->amount, 2) . " is being processed!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Add a payment method.
     */
    public function addPaymentMethod(Request $request)
    {
        $request->validate([
            'provider'       => 'required|in:' . implode(',', array_keys(PaymentService::PROVIDERS)),
            'account_number' => 'required|string|max:50',
            'account_name'   => 'nullable|string|max:200',
        ]);

        if (PaymentMethod::where('user_id', Auth::id())->count() >= 5) {
            return back()->with('error', 'Maximum 5 payment methods allowed.');
        }

        $method = PaymentMethod::create([
            'user_id'        => Auth::id(),
            'provider'       => $request->provider,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
        ]);

        AuditLogService::log('payment_method.added', $method);

        return back()->with('success', 'Payment method added successfully.');
    }
}

