@extends('admin.layout.template')

@section('content')
    @if (session('success'))
        <div class="alert alert-secondary alert-dismissible fade show " role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show " role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <style>
        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: #343a40 !important;
            /* Dark gray background */
            color: #ffffff !important;
            /* White text */
        }

        /* Apply on hover */
        select.form-select-sm:hover {
            background-color: #e2e6ea !important;
            /* Light grey hover effect */
        }

        /* Apply on focus */
        select.form-select-sm:focus {
            background-color: #fff3cd !important;
            /* Light yellow when focused */
            border-color: #ffeeba !important;
            color: #856404;
        }
    </style>
    <div class="container">
        <h2>Edit Product</h2>

        <form action="{{ route('update_product') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Hidden input for product ID -->
            <input type="hidden" name="id" value="{{ $product->id }}">

            <!-- Product Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="p_name"
                    value="{{ old('p_name', $product->p_name) }}" required>
                @error('p_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Product Price -->
            <div class="mb-3">
                <label for="price" class="form-label">Product Price</label>
                <input type="text" class="form-control" id="price" name="price"
                    value="{{ old('price', $product->price) }}" required>
                @error('price')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="qty" class="form-label">Product Total Stock</label>
                <input type="text" class="form-control" id="qty" name="qty"
                    value="{{ old('qty', $product->qty) }}" required>
                @error('qty')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Stock Status -->
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
                <label for="product_status" class="form-label">Product Status</label>
                <select class="form-select" id="product_status" name="product_status" required>
                    <option value="Active"
                        {{ old('status', $product->status) == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive"
                        {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive
                    </option>
                </select>
                @error('product_status')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            <!-- Product Image -->
            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="image" name="image">
                <img src="{{ asset('storage/images/' . $product->category_name . '/' . $product->image) }}"
                    alt="Current Image" style="width: 100px; height: auto; margin-top: 10px;">
            </div>

            <!-- Hidden Category ID -->
            <input type="hidden" name="category_id" value="{{ $product->category_id }}">

            <!-- Colors Section -->
            <div class="mb-3">
                <label class="form-label">Product Colors</label>
                <div id="colors-container">
                    @foreach ($product->colors as $index => $color)
                        <div class="row mb-2 color-row">
                            <input type="hidden" name="colors[{{ $index }}][id]" value="{{ $color->id }}">
                            <div class="col-md-5">
                                <select class="form-control" name="colors[{{ $index }}][color_name]">
                                    <option value="{{ $color->color_name }}">{{ $color->color_name }}</option>
                                    @foreach ($colors as $availableColor)
                                        <option value="{{ $availableColor->color_name }}"
                                            {{ $availableColor->id == $color->color_name ? 'selected' : '' }}>
                                            {{ $availableColor->color_name }}
                                        </option>
                                    @endforeach
                                    <option value="new_color">Add New Color</option>
                                </select>
                                <input type="text" class="form-control mt-2 new-color-input d-none"
                                    name="colors[{{ $index }}][new_color]" placeholder="Enter New Color">
                            </div>
                            <div class="col-md-5">
                                <input type="number" class="form-control" name="colors[{{ $index }}][quantity]"
                                    value="{{ $color->quantity }}" placeholder="Quantity" required>
                            </div>
                            {{-- <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-color">Remove</button>
                            </div> --}}
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-success mt-2" id="add-color">Add Color</button>
            </div>

            <!-- Submit and Cancel Buttons -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorsContainer = document.getElementById('colors-container');
            const addColorButton = document.getElementById('add-color');
            let colorIndex = {{ $product->colors->count() }};

            // Initialize Choices.js for all existing dropdowns
            function initializeChoices(selectElement) {
                return new Choices(selectElement, {
                    searchEnabled: true,
                    removeItemButton: true,
                    shouldSort: false, // Prevent sorting options
                    allowHTML: true,
                });
            }

            // Apply Choices.js to all existing select elements on page load
            document.querySelectorAll('select').forEach((selectElement) => {
                initializeChoices(selectElement);
            });

            // Add new color row dynamically
            addColorButton.addEventListener('click', function() {
                const newRow = document.createElement('div');
                newRow.className = 'row mb-3 color-row';
                newRow.innerHTML = `
                    <div class="col-md-5">
                        <select class="form-select color-select" name="colors[${colorIndex}][color_name]" required>
                            <option value="">Select Color</option>
                            @foreach ($colors as $availableColor)
                                <option value="{{ $availableColor->color_name }}">{{ $availableColor->color_name }}</option>
                            @endforeach
                            <option value="new_color">Add New Color</option>
                        </select>
                        <input type="text" class="form-control mt-2 new-color-input d-none" 
                            name="colors[${colorIndex}][new_color]" placeholder="Enter New Color">
                    </div>
                    <div class="col-md-5">
                        <input type="number" class="form-control" name="colors[${colorIndex}][quantity]" placeholder="Quantity" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger remove-color">Remove</button>
                    </div>
                `;
                colorsContainer.appendChild(newRow);

                // Apply Choices.js to the new dropdown
                const newSelect = newRow.querySelector('.color-select');
                initializeChoices(newSelect);

                // Add logic for "Add New Color"
                const newColorInput = newRow.querySelector('.new-color-input');
                newSelect.addEventListener('change', function() {
                    if (this.value === 'new_color') {
                        newColorInput.classList.remove('d-none');
                        newColorInput.setAttribute('required', 'true');
                    } else {
                        newColorInput.classList.add('d-none');
                        newColorInput.removeAttribute('required');
                    }
                });

                colorIndex++;
            });

            // Remove color row
            colorsContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-color')) {
                    e.target.closest('.color-row').remove();
                }
            });

            // Handle "Add New Color" logic for existing rows
            document.querySelectorAll('.color-select').forEach((selectElement) => {
                const newColorInput = selectElement.closest('.col-md-5').querySelector('.new-color-input');
                selectElement.addEventListener('change', function() {
                    if (this.value === 'new_color') {
                        newColorInput.classList.remove('d-none');
                        newColorInput.setAttribute('required', 'true');
                    } else {
                        newColorInput.classList.add('d-none');
                        newColorInput.removeAttribute('required');
                    }
                });
            });
        });
    </script>
@endsection
