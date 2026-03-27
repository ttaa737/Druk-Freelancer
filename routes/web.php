<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDisputeController;
use App\Http\Controllers\Admin\AdminJobController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminVerificationController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('jobs.index');
});

// Public job listings index (no wildcard)
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');

// Public reviews
Route::get('/users/{user}/reviews', [ReviewController::class, 'index'])->name('reviews.index');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'audit'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Profile ──────────────────────────────────────────────────────────────
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/documents', [ProfileController::class, 'uploadDocument'])->name('documents');
        Route::post('/phone-otp', [ProfileController::class, 'sendPhoneOTP'])->name('phone.otp');
        Route::post('/phone-verify', [ProfileController::class, 'verifyPhone'])->name('phone.verify');
    });
    
    // Public profile (must be after /profile/edit to avoid route conflict)
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show')->withoutMiddleware(['auth', 'verified', 'audit']);

    // ── Settings ─────────────────────────────────────────────────────────────
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/account', [SettingsController::class, 'updateAccount'])->name('account.update');
        Route::put('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications.update');
        Route::put('/privacy', [SettingsController::class, 'updatePrivacy'])->name('privacy.update');
        Route::delete('/payment-method/{paymentMethod}', [SettingsController::class, 'deletePaymentMethod'])->name('payment-method.delete');
        Route::delete('/account', [SettingsController::class, 'deleteAccount'])->name('account.delete');
    });

    // ── Jobs ─────────────────────────────────────────────────────────────────
    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::get('/post', [JobController::class, 'create'])->name('create')->middleware('role:job_poster');
        Route::post('/', [JobController::class, 'store'])->name('store')->middleware('role:job_poster');
        Route::get('/{job}/edit', [JobController::class, 'edit'])->name('edit');
        Route::put('/{job}', [JobController::class, 'update'])->name('update');
        Route::delete('/{job}', [JobController::class, 'destroy'])->name('destroy');
        Route::get('/my-jobs', [JobController::class, 'myJobs'])->name('my');
    });
    
    // Public job show (must be after /jobs/post and /jobs/my-jobs to avoid route conflict)
    Route::get('/jobs/{slug}', [JobController::class, 'show'])->name('jobs.show')->withoutMiddleware(['auth', 'verified', 'audit']);

    // ── Proposals ────────────────────────────────────────────────────────────
    Route::prefix('proposals')->name('proposals.')->group(function () {
        Route::post('/jobs/{job}', [ProposalController::class, 'store'])->name('store')->middleware('role:freelancer');
        Route::get('/my', [ProposalController::class, 'myProposals'])->name('my');
        Route::get('/{proposal}', [ProposalController::class, 'show'])->name('show');
        Route::post('/{proposal}/shortlist', [ProposalController::class, 'shortlist'])->name('shortlist');
        Route::post('/{proposal}/award', [ProposalController::class, 'award'])->name('award');
        Route::post('/{proposal}/reject', [ProposalController::class, 'reject'])->name('reject');
        Route::delete('/{proposal}/withdraw', [ProposalController::class, 'withdraw'])->name('withdraw');
    });

    // Job proposals listing (poster view)
    Route::get('/jobs/{job}/proposals', [ProposalController::class, 'index'])->name('jobs.proposals');

    // ── Contracts ────────────────────────────────────────────────────────────
    Route::prefix('contracts')->name('contracts.')->group(function () {
        Route::get('/', [ContractController::class, 'index'])->name('index');
        Route::get('/{contract}', [ContractController::class, 'show'])->name('show');
        Route::post('/{contract}/fund-escrow', [ContractController::class, 'fundEscrow'])->name('fund');
        Route::post('/{contract}/sign', [ContractController::class, 'sign'])->name('sign');
        Route::post('/{contract}/cancel', [ContractController::class, 'cancel'])->name('cancel');
    });

    // ── Milestones ───────────────────────────────────────────────────────────
    Route::prefix('milestones')->name('milestones.')->group(function () {
        Route::post('/{milestone}/submit', [MilestoneController::class, 'submit'])->name('submit');
        Route::post('/{milestone}/approve', [MilestoneController::class, 'approve'])->name('approve');
        Route::post('/{milestone}/revision', [MilestoneController::class, 'requestRevision'])->name('revision');
    });

    // ── Messages ──────────────────────────────────────────────────────────────
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::post('/start', [MessageController::class, 'start'])->name('start');
        Route::get('/{conversation}', [MessageController::class, 'show'])->name('show');
        Route::post('/{conversation}/send', [MessageController::class, 'send'])->name('send');
        Route::post('/read/{message}', [MessageController::class, 'markRead'])->name('read');
        Route::get('/{conversation}/poll/{lastId?}', [MessageController::class, 'poll'])->name('poll');
    });

    // ── Wallet ────────────────────────────────────────────────────────────────
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('/deposit', [WalletController::class, 'showDeposit'])->name('deposit.form');
        Route::post('/deposit', [WalletController::class, 'deposit'])->name('deposit');
        Route::get('/withdraw', [WalletController::class, 'showWithdraw'])->name('withdraw.form');
        Route::post('/withdraw/otp', [WalletController::class, 'sendWithdrawOTP'])->name('withdraw.otp');
        Route::post('/withdraw', [WalletController::class, 'withdraw'])->name('withdraw');
        Route::post('/payment-method', [WalletController::class, 'addPaymentMethod'])->name('payment-method.add');
    });

    // ── Reviews ───────────────────────────────────────────────────────────────
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/contracts/{contract}/create', [ReviewController::class, 'create'])->name('create');
        Route::post('/contracts/{contract}', [ReviewController::class, 'store'])->name('store');
    });

    // ── Disputes ──────────────────────────────────────────────────────────────
    Route::prefix('disputes')->name('disputes.')->group(function () {
        Route::get('/', [DisputeController::class, 'index'])->name('index');
        Route::get('/contracts/{contract}/create', [DisputeController::class, 'create'])->name('create');
        Route::post('/contracts/{contract}', [DisputeController::class, 'store'])->name('store');
        Route::get('/{dispute}', [DisputeController::class, 'show'])->name('show');
        Route::post('/{dispute}/evidence', [DisputeController::class, 'addEvidence'])->name('evidence');
        Route::post('/{dispute}/comment', [DisputeController::class, 'addComment'])->name('comment');
    });

    // ── Notifications ─────────────────────────────────────────────────────────
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllRead'])->name('read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::get('/count', [NotificationController::class, 'unreadCount'])->name('count');
    });

    /*
    |----------------------------------------------------------------------
    | Admin Routes
    |----------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
            Route::post('/{user}/suspend', [AdminUserController::class, 'suspend'])->name('suspend');
            Route::post('/{user}/ban', [AdminUserController::class, 'ban'])->name('ban');
            Route::post('/{user}/activate', [AdminUserController::class, 'activate'])->name('activate');
            Route::post('/{user}/verify', [AdminUserController::class, 'verify'])->name('verify');
            Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
        });

        // Jobs
        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/', [AdminJobController::class, 'index'])->name('index');
            Route::get('/{job}', [AdminJobController::class, 'show'])->name('show');
            Route::post('/{job}/feature', [AdminJobController::class, 'toggleFeatured'])->name('feature');
            Route::post('/{job}/moderate', [AdminJobController::class, 'moderate'])->name('moderate');
            Route::post('/{id}/restore', [AdminJobController::class, 'restore'])->name('restore');
        });

        // Disputes
        Route::prefix('disputes')->name('disputes.')->group(function () {
            Route::get('/', [AdminDisputeController::class, 'index'])->name('index');
            Route::get('/{dispute}', [AdminDisputeController::class, 'show'])->name('show');
            Route::post('/{dispute}/assign', [AdminDisputeController::class, 'assign'])->name('assign');
            Route::post('/{dispute}/resolve', [AdminDisputeController::class, 'resolve'])->name('resolve');
        });

        // Verifications
        Route::prefix('verifications')->name('verifications.')->group(function () {
            Route::get('/', [AdminVerificationController::class, 'index'])->name('index');
            Route::get('/{document}', [AdminVerificationController::class, 'show'])->name('show');
            Route::post('/{document}/approve', [AdminVerificationController::class, 'approve'])->name('approve');
            Route::post('/{document}/reject', [AdminVerificationController::class, 'reject'])->name('reject');
        });

        // Categories & Skills
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [AdminCategoryController::class, 'index'])->name('index');
            Route::post('/', [AdminCategoryController::class, 'storeCategory'])->name('store');
            Route::put('/{category}', [AdminCategoryController::class, 'updateCategory'])->name('update');
            Route::delete('/{category}', [AdminCategoryController::class, 'destroyCategory'])->name('destroy');
            Route::post('/{category}/skills', [AdminCategoryController::class, 'storeSkill'])->name('skills.store');
            Route::delete('/{category}/skills/{skill}', [AdminCategoryController::class, 'destroySkill'])->name('skills.destroy');
        });

        // Transactions
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [AdminTransactionController::class, 'index'])->name('index');
            Route::get('/{transaction}', [AdminTransactionController::class, 'show'])->name('show');
            Route::post('/{transaction}/approve', [AdminTransactionController::class, 'approveWithdrawal'])->name('approve');
            Route::post('/{transaction}/reject', [AdminTransactionController::class, 'rejectWithdrawal'])->name('reject');
        });
    });
});

