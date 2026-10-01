@extends('admin.layouts.app')

@section('title', 'Users')

@section('content')
    <div class="page-header">
        <h1>Users</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add user</a>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if ($user->is_admin)
                            <span class="badge">Admin</span>
                        @else
                            <span class="badge badge-muted">User</span>
                        @endif
                    </td>
                    <td class="actions">
                        @unless ($user->is_admin)
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        @else
                            <span class="badge badge-muted">Protected</span>
                        @endunless
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No users yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $users->links('pagination.admin') }}
    </div>
@endsection
