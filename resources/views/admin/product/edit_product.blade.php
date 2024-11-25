@extends('admin.layout.template') <!-- Assuming you have a layout file for the admin panel -->

@section('content')
    <div class="container">
        <h2>Edit Product</h2>

        <form action="{{ route('update_product') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="id" value="{{ $product->id }}">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="p_name"
                    value="{{ old('p_name', $product->p_name) }}" required>
                @error('p_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Product Price</label>
                <input type="text" class="form-control" id="price" name="price"
                    value="{{ old('price', $product->price) }}" required>
                @error('price')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <input type="hidden" name="category_name" value="{{ $product->category_name }}">

            <div class="mb-3">
                <label for="stock_status" class="form-label">Stock Status</label>
                <select class="form-select" id="stock_status" name="stock_status" required>
                    <option value="in_stock"
                        {{ old('stock_status', $product->stock_status) == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="out_of_stock"
                        {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock
                    </option>
                </select>
                @error('stock_status')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="image" name="image">
                <img src="{{ asset('storage/images/' . $product->category_name . '/' . $product->image) }}"
                    alt="Current Image" style="width: 100px; height: auto; margin-top: 10px;">
            </div>

            <div class="mb-3">
                {{-- <label for="category_id" class="form-label">Category ID (Hidden)</label> --}}
                <input type="hidden" name="category_id" value="{{ $product->category_id }}">
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('dashboard_2') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
