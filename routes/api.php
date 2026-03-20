<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Sanctum-protected JSON API routes.
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());

    // Notifications
    Route::get('/notifications/count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    // Messages polling
    Route::get('/conversations/{conversation}/poll/{lastId?}', [MessageController::class, 'poll']);
    Route::post('/conversations/{conversation}/send', [MessageController::class, 'send']);
});

