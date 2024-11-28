@extends('layout.app')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6>Edit Product</h6>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Back to Products</a>
                </div>
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                            value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" name="category" id="category" class="form-control @error('category') is-invalid @enderror" 
                            value="{{ old('category', $product->category) }}" required>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Size -->
                    <div class="mb-3">
                        <label for="size" class="form-label">Size</label>
                        <select name="size" id="size" class="form-select" >
                            <option value="">Select Size</option>
                            <option value="Small" {{ $product->size === 'Small' ? 'selected' : '' }}>Small</option>
                            <option value="Large" {{ $product->size === 'Large' ? 'selected' : '' }}>Large</option>
                            <option value="Medium" {{ $product->size === 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="Extra Large" {{ $product->size === 'Extra Large' ? 'selected' : '' }}>Extra Large</option>
                        </select>
                    </div>                    

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                            rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" 
                            value="{{ old('price', $product->price) }}" step="0.01" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stock -->
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" 
                            value="{{ old('stock', $product->stock) }}" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($product->image_url)
                            <div class="mt-3">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 150px; height: auto;">
                                <p class="text-muted mt-1">Current Image</p>
                            </div>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
