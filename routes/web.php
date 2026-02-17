<?php

use App\Http\Controllers\AuthController;
use App\Support\Permissions\PermissionService;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/roles', fn () => 'ok')->middleware(['auth', 'permission:roles.read']);

Route::get('/health', function () {
    return response()->json([
        'ok' => true,
        'user_id' => auth()->user(),
    ]);
})->middleware('auth');

Route::get('/me/permissions', function (PermissionService $permission) {
    return response()->json([
        'user_id' => auth()->id(),
        'role_id' => auth()->user()->role_id ?? null,
        'keys' => $permission->keysForCurrentUser()
    ]);
})->middleware('auth');;
