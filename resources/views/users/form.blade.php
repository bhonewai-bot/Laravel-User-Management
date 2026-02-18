@extends('layouts.app')

@section('content')
@php
  $isEdit = ($mode === 'edit');
  $genderValue = old('gender', isset($user) && $user->gender !== null ? (string) ((int) $user->gender) : '');
@endphp

<div class="row justify-content-center">
  <div class="col-12 col-lg-9">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0">{{ $isEdit ? 'Edit User' : 'Create User' }}</h1>
      <a class="btn btn-outline-secondary" href="/users">Back</a>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger">
        {{ $errors->first() }}
      </div>
    @endif

    <div class="card shadow-sm border-0">
      <div class="card-body p-4">
        <form method="post" action="{{ $isEdit ? '/users/'.$user->id : '/users' }}">
          @csrf

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Name</label>
              <input class="form-control" name="name" value="{{ old('name', $user->name ?? '') }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Username</label>
              <input class="form-control" name="username" value="{{ old('username', $user->username ?? '') }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Role</label>
              <select class="form-select" name="role_id" required>
                @foreach($roles as $r)
                  <option value="{{ $r->id }}"
                    {{ (int)old('role_id', $user->role_id ?? 0) === (int)$r->id ? 'selected' : '' }}>
                    {{ $r->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input class="form-control" name="email" value="{{ old('email', $user->email ?? '') }}">
            </div>

            <div class="col-md-6">
              <label class="form-label">Phone</label>
              <input class="form-control" name="phone" value="{{ old('phone', $user->phone ?? '') }}">
            </div>

            <div class="col-12">
              <label class="form-label">Address</label>
              <input class="form-control" name="address" value="{{ old('address', $user->address ?? '') }}">
            </div>

            <div class="col-md-6">
              <label class="form-label">Gender</label>
              <select class="form-select" name="gender">
                <option value="" {{ $genderValue === '' ? 'selected' : '' }}>-- select --</option>
                <option value="1" {{ $genderValue === '1' ? 'selected' : '' }}>Male</option>
                <option value="0" {{ $genderValue === '0' ? 'selected' : '' }}>Female</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">{{ $isEdit ? 'New Password (optional)' : 'Password' }}</label>
              <input class="form-control" type="password" name="password" {{ $isEdit ? '' : 'required' }}>
              @if($isEdit)
                <div class="form-text">Leave blank to keep current password.</div>
              @endif
            </div>

            <div class="col-md-6 d-flex align-items-end">
              <div class="form-check mb-2">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                  {{ (string) old('is_active', isset($user) && $user->is_active ? '1' : '0') === '1' ? 'checked' : '' }}>
                <label class="form-check-label">Active</label>
              </div>
            </div>
          </div>

          <div class="mt-4 d-flex gap-2">
            <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Update' : 'Create' }}</button>
            <a class="btn btn-outline-secondary" href="/users">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
