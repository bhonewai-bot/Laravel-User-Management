@extends('layouts.app')

@section('content')
@php
  $isEdit = ($mode === 'edit');
  $selected = array_fill_keys($selectedPermissionIds, true);
@endphp

<div class="row justify-content-center">
  <div class="col-12 col-xl-10">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0">{{ $isEdit ? 'Edit Role' : 'Create Role' }}</h1>
      <a class="btn btn-outline-secondary" href="/roles">Back</a>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger">
        {{ $errors->first() }}
      </div>
    @endif

    <div class="card shadow-sm border-0">
      <div class="card-body p-4">
        <form method="post" action="{{ $isEdit ? '/roles/'.$role->id : '/roles' }}">
          @csrf

          <div class="mb-4">
            <label for="name" class="form-label">Role name</label>
            <input id="name" class="form-control" name="name" value="{{ old('name', $role->name ?? '') }}" required>
          </div>

          <h2 class="h5 mb-3">Role Permissions</h2>

          <div class="row g-3">
            @foreach($features as $f)
              <div class="col-12 col-lg-6">
                <div class="card h-100 border">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h3 class="h6 mb-0">{{ $f->name }}</h3>
                      <div class="form-check">
                        <input
                          class="form-check-input"
                          type="checkbox"
                          id="feature-all-{{ $f->id }}"
                          onclick="toggleFeature({{ $f->id }}, this.checked)"
                        >
                        <label class="form-check-label small" for="feature-all-{{ $f->id }}">Select all</label>
                      </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3">
                      @foreach($f->permissions as $p)
                        @php $checked = isset($selected[$p->id]); @endphp
                        <div class="form-check">
                          <input
                            class="form-check-input perm-{{ $f->id }}"
                            type="checkbox"
                            id="permission-{{ $p->id }}"
                            name="permission_ids[]"
                            value="{{ $p->id }}"
                            {{ $checked ? 'checked' : '' }}
                          >
                          <label class="form-check-label" for="permission-{{ $p->id }}">{{ $p->name }}</label>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <div class="mt-4 d-flex gap-2">
            <button class="btn btn-primary" type="submit">
              {{ $isEdit ? 'Update' : 'Create' }}
            </button>
            <a class="btn btn-outline-secondary" href="/roles">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleFeature(featureId, checked) {
    document.querySelectorAll('.perm-' + featureId).forEach(cb => cb.checked = checked);
  }
</script>
@endsection
