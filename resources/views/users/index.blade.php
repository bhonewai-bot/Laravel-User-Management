@extends('layouts.app')

@section('content')
@php
  $perm = app(\App\Support\Permissions\PermissionService::class);
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Users</h1>

  @if($perm->hasPermission('users.create'))
    <a class="btn btn-primary" href="/users/create">Create User</a>
  @endif
</div>

<div class="card shadow-sm border-0">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Username</th>
            <th>Role</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Active</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $u)
            <tr>
              <td>{{ $u->id }}</td>
              <td>{{ $u->name }}</td>
              <td>{{ $u->username }}</td>
              <td>{{ $u->role?->name ?? '---' }}</td>
              <td>{{ $u->email ?? '---' }}</td>
              <td>{{ $u->phone ?? '---' }}</td>
              <td>
                @if($perm->hasPermission('users.update'))
                  <form method="post" action="/users/{{ $u->id }}/toggle-active" class="d-flex align-items-center gap-2 mb-0">
                    @csrf
                    <div class="form-check form-switch mb-0">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        role="switch"
                        id="active-switch-{{ $u->id }}"
                        {{ $u->is_active ? 'checked' : '' }}
                        onchange="this.form.submit()"
                      >
                      <label class="form-check-label small text-muted" for="active-switch-{{ $u->id }}">
                        {{ $u->is_active ? 'On' : 'Off' }}
                      </label>
                    </div>
                    <noscript>
                      <button type="submit" class="btn btn-sm btn-outline-secondary">Apply</button>
                    </noscript>
                  </form>
                @else
                  <span class="badge {{ $u->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                    {{ $u->is_active ? 'Active' : 'Inactive' }}
                  </span>
                @endif
              </td>
              <td class="text-end">
                @if($perm->hasPermission('users.update'))
                  <a class="btn btn-sm btn-outline-primary" href="/users/{{ $u->id }}/edit">Edit</a>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">No users found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
