@php($product = $product ?? null)

<label>
    Name
    <input type="text" name="name" value="{{ old('name', $product?->name) }}" required>
</label>

<label>
    Slug
    <input type="text" name="slug" value="{{ old('slug', $product?->slug) }}" required>
</label>

<label>
    Description
    <textarea name="description">{{ old('description', $product?->description) }}</textarea>
</label>

<label>
    Price
    <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $product?->price) }}" required>
</label>

<label>
    Stock
    <input type="number" name="stock" min="0" value="{{ old('stock', $product?->stock ?? 0) }}" required>
</label>
