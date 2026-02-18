<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Support\Permissions\PermissionService;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:roles.read');

    Route::get('/roles/create', [RoleController::class, 'create'])->middleware('permission:roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:roles.create');

    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:roles.update');
    Route::post('/roles/{role}', [RoleController::class, 'update'])->middleware('permission:roles.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:users.read');

    Route::get('/users/create', [UserController::class, 'create'])->middleware('permission:users.create');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:users.create');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->middleware('permission:users.update');
    Route::post('/users/{user}', [UserController::class, 'update'])->middleware('permission:users.update');

    Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->middleware('permission:users.update');
});

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
