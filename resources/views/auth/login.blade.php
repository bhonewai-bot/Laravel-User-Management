<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
</head>
<body style="font-family: system-ui; padding: 40px;">
  <h2>Login</h2>

  @if ($errors->any())
    <div style="color: red; margin-bottom: 16px;">
      {{ $errors->first() }}
    </div>
  @endif

  <form method="post" action="/login">
    @csrf

    <div style="margin-bottom: 10px;">
      <label>Username</label><br>
      <input name="username" value="{{ old('username') }}" required>
    </div>

    <div style="margin-bottom: 10px;">
      <label>Password</label><br>
      <input name="password" type="password" required>
    </div>

    <button type="submit">Login</button>
  </form>
</body>
</html>