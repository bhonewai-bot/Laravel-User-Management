<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Role;
use App\Support\Permissions\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::query()->orderBy('id')->get();

        return view('roles.index', [
            'roles' => $roles
        ]);
    }

    public function create()
    {
        $features = Feature::query()
            ->with(['permissions' => fn ($q) => $q->orderBy('name')])
            ->orderBy('name')
            ->get();

        return view('roles.form', [
            'mode' => 'create',
            'role' => null,
            'features' => $features,
            'selectedPermissionIds' => [],
        ]);
    }

    public function store(Request $request, PermissionService $permission)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:roles,name'],
            'permission_ids' => ['array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ]);

        $permissionIds = $data['permission_ids'] ?? [];

        DB::transaction(function () use ($data, $permissionIds, &$role) {
            $role = Role::create(['name' => $data['name']]);
            $role->permissions()->sync($permissionIds);
        });

        $permission->clearRoleCache((int)$role->id);

        return redirect('/roles');
    }

    public function edit(Role $role)
    {
        $features = Feature::query()
            ->with(['permissions' => fn ($q) => $q->orderBy('name')])
            ->orderBy('name')
            ->get();

        $selected = $role->permissions()->pluck('permissions.id')->all();

        return view('roles.form', [
            'mode' => 'edit',
            'role' => $role,
            'features' => $features,
            'selectedPermissionIds' => $selected,
        ]);
    }

    public function update(Request $request, Role $role, PermissionService $permission)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('roles', 'name')->ignore($role->id)],
            'permission_ids' => ['array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ]);

        $permissionIds = $data['permission_ids'] ?? [];

        DB::transaction(function () use ($role, $data, $permissionIds) {
            $role->update([
                'name' => $data['name']
            ]);
            $role->permissions()->sync($permissionIds);
        });

        $permission->clearRoleCache((int)$role->id);

        return redirect('/roles');
    }
}
