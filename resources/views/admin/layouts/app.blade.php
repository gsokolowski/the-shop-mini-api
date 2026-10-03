<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — Shop Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <header class="topbar">
        <div class="brand"><a href="{{ route('admin.dashboard') }}">Shop Admin</a></div>
        <nav>
            <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>Dashboard</a>
            <a href="{{ route('admin.users.index') }}" @class(['active' => request()->routeIs('admin.users.*')])>Users</a>
            <a href="{{ route('admin.products.index') }}" @class(['active' => request()->routeIs('admin.products.*')])>Products</a>
        </nav>
        <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </header>

    <main class="container">
        @if (session('success'))
            <!-- User Product created, updated deleted -->
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <!-- Admin users cannot be updated ect -->
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <!-- $errors — validation error list -->
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- here goes content Product CRUD  User CRUD and later Orders -->
        @yield('content')
    </main>
</body>
</html>
