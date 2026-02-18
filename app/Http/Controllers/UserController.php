<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\Role;
use App\Support\Permissions\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = AdminUser::query()
            ->with('role:id,name')
            ->orderByDesc('id')
            ->get();

        return view('users.index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        $roles = Role::query()->orderBy('id')->get(['id', 'name']);

        return view('users.form', [
            'mode' => 'create',
            'user'=> null,
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:100', Rule::unique('admin_users', 'username')],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100', Rule::unique('admin_users', 'email')],
            'address' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:0,1'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
            'is_active' => ['nullable', 'boolean']
        ]);

        AdminUser::query()->create([
            'name' => $data['name'],
            'username' => $data['username'],
            'role_id' => (int)$data['role_id'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'gender' => array_key_exists('gender', $data) ? (int)$data['gender'] : null,
            'password' => Hash::make($data['password']),
            "is_active" => $request->boolean('is_active'),
        ]);

        return redirect('/users');
    }

    public function edit(AdminUser $user)
    {
        $roles = Role::query()->orderBy('id')->get(['id', 'name']);

        return view('users.form', [
            'mode' => 'edit',
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, AdminUser $user, PermissionService $permission)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:100', Rule::unique('admin_users', 'username')->ignore($user->id)],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100', Rule::unique('admin_users', 'email')->ignore($user->id)],
            'address' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:0,1'],
            'password' => ['nullable', 'string', 'min:6', 'max:255'],
            'is_active' => ['nullable', 'boolean']
        ]);

        $oldRoleId = (int)$user->role_id;

        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->role_id = (int)$data['role_id'];
        $user->phone = $data['phone'] ?? null;
        $user->email = $data['email'] ?? null;
        $user->address = $data['address'] ?? null;
        $user->gender = array_key_exists('gender', $data) ? (int)$data['gender'] : null;
        $user->is_active = $request->boolean('is_active');

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $newRoleId = (int)$user->role_id;
        if ($oldRoleId !== $newRoleId) {
            $permission->clearRoleCache($oldRoleId);
            $permission->clearRoleCache($newRoleId);
        }

        return redirect('/users');
    }

    public function toggleActive(AdminUser $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return redirect('/users');
    }
}
