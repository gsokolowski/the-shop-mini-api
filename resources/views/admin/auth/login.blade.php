<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — Shop Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="auth-body">
    <main class="auth-card">
        <h1>Admin Login</h1>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <label>
                Email
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </label>
            <label>
                Password
                <input type="password" name="password" required>
            </label>
            <label class="checkbox">
                <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                Remember me
            </label>
            <button type="submit" class="btn btn-primary">Log in</button>
        </form>
    </main>
</body>
</html>
