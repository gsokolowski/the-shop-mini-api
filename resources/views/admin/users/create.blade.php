@extends('admin.layouts.app')

@section('title', 'Create User')

@section('content')
    <div class="page-header">
        <h1>Create user</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.users.store') }}" class="form-grid">
            @csrf
            @include('admin.users._form')
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
