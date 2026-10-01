@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
    <div class="page-header">
        <h1>Products</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add product</a>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Price</th>
                <th>Stock</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->slug }}</td>
                    <td>{{ number_format((float) $product->price, 2) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td class="actions">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No products yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $products->links('pagination.admin') }}
    </div>
@endsection
