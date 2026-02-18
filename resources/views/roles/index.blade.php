@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="mb-0">Roles</h2>

  @if(app(\App\Support\Permissions\PermissionService::class)->hasPermission('roles.create'))
    <a class="btn btn-primary" href="/roles/create">Create Role</a>
  @endif
</div>

<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-striped table-hover mb-0 align-middle">
      <thead class="table-dark">
        <tr>
          <th style="width:70px;">ID</th>
          <th>Name</th>
          <th style="width:160px;">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($roles as $r)
          <tr>
            <td>{{ $r->id }}</td>
            <td>{{ $r->name }}</td>
            <td>
              @if(app(\App\Support\Permissions\PermissionService::class)->hasPermission('roles.update'))
                <a class="btn btn-sm btn-outline-primary" href="/roles/{{ $r->id }}/edit">Edit</a>
              @else
                ---
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection