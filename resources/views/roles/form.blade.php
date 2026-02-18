@extends('layouts.app')

@section('content')
@php
  $isEdit = ($mode === 'edit');
  $selected = array_fill_keys($selectedPermissionIds, true);
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="mb-0">{{ $isEdit ? 'Edit Role' : 'Create Role' }}</h2>
  <a class="btn btn-outline-secondary" href="/roles">Back</a>
</div>

@if ($errors->any())
  <div class="alert alert-danger">
    {{ $errors->first() }}
  </div>
@endif

<form method="post" action="{{ $isEdit ? '/roles/'.$role->id : '/roles' }}">
  @csrf

  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <label class="form-label">Role name</label>
      <input class="form-control" name="name" value="{{ old('name', $role->name ?? '') }}" required>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-white">
      <strong>Role Permissions</strong>
    </div>
    <div class="card-body">
      @foreach($features as $f)
        <div class="border rounded p-3 mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <strong>{{ $f->name }}</strong>
            <button type="button" class="btn btn-sm btn-outline-secondary"
                    onclick="toggleFeature({{ $f->id }}, true)">Select all</button>
          </div>

          <div class="d-flex flex-wrap gap-3">
            @foreach($f->permissions as $p)
              @php $checked = isset($selected[$p->id]); @endphp
              <label class="form-check form-check-inline">
                <input class="form-check-input perm-{{ $f->id }}"
                       type="checkbox"
                       name="permission_ids[]"
                       value="{{ $p->id }}"
                       {{ $checked ? 'checked' : '' }}>
                <span class="form-check-label">{{ $p->name }}</span>
              </label>
            @endforeach
          </div>
        </div>
      @endforeach

      <button class="btn btn-primary" type="submit">
        {{ $isEdit ? 'Update' : 'Create' }}
      </button>
    </div>
  </div>
</form>

<script>
  function toggleFeature(featureId, checked) {
    document.querySelectorAll('.perm-' + featureId).forEach(cb => cb.checked = checked);
  }
</script>
@endsection