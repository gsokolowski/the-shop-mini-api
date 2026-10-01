@extends('admin.layouts.app')

@section('title', 'Create Product')

@section('content')
    <div class="page-header">
        <h1>Create product</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.products.store') }}" class="form-grid">
            @csrf
            @include('admin.products._form')
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
