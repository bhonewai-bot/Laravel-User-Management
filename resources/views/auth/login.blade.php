@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-4">
  <div class="col-12 col-md-8 col-lg-5">
    <div class="card shadow-sm border-0">
      <div class="card-body p-4">
        <h1 class="h4 mb-3">Login</h1>

        @if ($errors->any())
          <div class="alert alert-danger mb-3">
            {{ $errors->first() }}
          </div>
        @endif

        <form method="post" action="/login">
          @csrf

          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input id="username" name="username" class="form-control" value="{{ old('username') }}" required>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input id="password" name="password" type="password" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
