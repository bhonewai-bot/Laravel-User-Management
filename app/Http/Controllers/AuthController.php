<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Support\Permissions\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        $user = AdminUser::query()->where('username', $data['username'])->first();

        if (!$user) {
            return back()
                ->withErrors(['username' => 'Invalid credentials'])
                ->onlyInput('username');
        }

        if (!$user->is_active) {
            return back()
                ->withErrors(['username' => 'User is inactive'])
                ->onlyInput('username');
        }

        if (!Hash::check($data['password'], $user->password)) {
            return back()
                ->withErrors(['username' => 'Invalid credentials'])
                ->onlyInput('username');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/health');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        app(PermissionService::class)->clearCurrentUserSessionCache();

        return redirect('/login');
    }
}
