<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
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
