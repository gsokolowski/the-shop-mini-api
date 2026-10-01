@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="page-header">
        <h1>Edit user</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="form-grid">
            @csrf
            @method('PUT')
            @include('admin.users._form')
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
@endsection
