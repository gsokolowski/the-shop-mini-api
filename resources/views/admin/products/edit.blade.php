@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="page-header">
        <h1>Edit product</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" class="form-grid">
            @csrf
            @method('PUT')
            @include('admin.products._form')
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
@endsection
