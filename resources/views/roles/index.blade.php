@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Roles</h1>

  @if(app(\App\Support\Permissions\PermissionService::class)->hasPermission('roles.create'))
    <a class="btn btn-primary" href="/roles/create">Create Role</a>
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
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($roles as $r)
            <tr>
              <td>{{ $r->id }}</td>
              <td>{{ $r->name }}</td>
              <td class="text-end">
                @if(app(\App\Support\Permissions\PermissionService::class)->hasPermission('roles.update'))
                  <a class="btn btn-sm btn-outline-primary" href="/roles/{{ $r->id }}/edit">Edit</a>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center text-muted py-4">No roles found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
