@extends('admin.layout.template')
@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Choices.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <!-- Choices.js JS -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">




    <style>
        /* Darken the hover effect for dropdown options */
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


        button.btn {
            height: 40px;
            line-height: 1.5;
            padding: 8px 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 80px;
        }

        button i {
            width: 1em;
            height: 1em;
            display: inline-block;
        }

        .pagination {
            margin: 0;
            /* Remove default margin */
            padding: 0;
            /* Remove default padding */
            list-style: none;
            /* Remove bullet points */
            display: flex;
            /* Align pagination links inline */
            justify-content: center;
            /* Center the links */
        }

        .pagination li {
            margin: 0 5px;
            /* Add spacing between links */
        }

        .pagination li a,
        .pagination li span {
            display: block;
            padding: 8px 12px;
            /* Adjust padding for better click area */
            text-decoration: none;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            color: #007bff;
        }

        .pagination li a:hover {
            background-color: #e9ecef;
            color: #0056b3;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .table th,
        .table td {
            padding: 12px;
            vertical-align: middle;
        }

        .table-light {
            background-color: #f8f9fa;
        }

        .content-icon i {
            font-size: 18px;
        }

        .color-item {
            align-items: center;
            /* Vertically align fields */
        }

        .color-item .form-control,
        .color-item .form-select {
            height: calc(2.25rem + 2px);
            /* Match height of input and select */
        }

        .color-item .remove-color {
            padding: 0.375rem 0.75rem;
        }
    </style>
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
    <script>
        @if (session('login_success'))
            toastr.success('{{ session('login_success') }}', 'Welcome Back!', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000
            });
        @endif
    </script>




    <div class="mb-6">
        <h3>Products List</h3>
        <div class="d-flex mb-3" style="
        justify-content: end;
    ">

            <form id="cart-form" action="{{ route('add_to_cart') }}" method="POST">
                @csrf
                <input type="hidden" id="cart-product-ids" name="product_ids">
            </form>

            <button id="add-to-cart-btn" class="btn btn-primary me-4" onclick="saveCartToDatabase()">Save Cart</button>

            <button class="btn btn-primary me-4" type="button" data-bs-toggle="modal" data-bs-target="#addDealModal">
                <i class="fas fa-plus me-2"></i> Add New Product
            </button>
            <div class="d-flex justify-content-between mb-3">
                <div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="filterDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-filter me-2"></i> Filter Products
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                        <li><a class="dropdown-item filter-option"
                                href="{{ route('products.filter', ['filter' => 'p_new_to_old']) }}">Product: New to Old</a>
                        </li>
                        <li><a class="dropdown-item filter-option"
                                href="{{ route('products.filter', ['filter' => 'p_old_to_new']) }}">Product: Old to New</a>
                        </li>
                        <li><a class="dropdown-item filter-option"
                                href="{{ route('products.filter', ['filter' => 'price_low_to_high']) }}">Price: Low to
                                High</a></li>
                        <li><a class="dropdown-item filter-option"
                                href="{{ route('products.filter', ['filter' => 'price_high_to_low']) }}">Price: High to
                                Low</a></li>
                        <li><a class="dropdown-item filter-option"
                                href="{{ route('products.filter', ['filter' => 'c_new_to_old']) }}">Category: New to Old</a>
                        </li>
                        <li><a class="dropdown-item filter-option"
                                href="{{ route('products.filter', ['filter' => 'c_old_to_new']) }}">Category: Old to
                                New</a>
                        </li>
                        <li><a class="dropdown-item filter-option"
                                href="{{ route('products.filter', ['filter' => 'c_a_to_z']) }}">Category: A to Z</a></li>
                        <li><a class="dropdown-item filter-option"
                                href="{{ route('products.filter', ['filter' => 'c_z_to_a']) }}">Category: Z to A</a></li>
                        <li><a class="dropdown-item filter-option"
                                href="{{ route('products.filter', ['filter' => 'latest_updated']) }}">Latest Updated
                                Product</a></li>
                    </ul>
                </div>
            </div>

        </div>


        <form method="GET" action="{{ route('product') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search by Design No or Category" aria-label="Search" />
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        {{-- @dd($products); --}}

        <div id="tableExample3"
            data-list='{"valueNames":["no","category_name","name","image","stock_status","price","status"],"page":10,"pagination":true}'>
            <div class="search-box mb-3 mx-auto">
                <!--<form class="position-relative">-->
                <!--    <input class="form-control rounded-pill search-input form-control-sm" type="search" placeholder="Search"-->
                <!--           aria-label="Search"/>-->
                <!--</form>-->
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered  table-sm fs-9 mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Image</th>
                            <th class="text-center">Design No</th>
                            <th class="text-center">Total Stock</th>
                            <th class="text-center">Category Name</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Stock Status</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Edit</th>
                            <th class="text-center">Delete</th>
                            <th class="text-center">Add to Cart</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach ($products as $item)
                            <tr class="align-middle">
                                <td class="no text-center">
                                    {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                                <td class="image text-center">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal"
                                        onclick="showImage('{{ asset('storage/images/' . $item['category_name'] . '/' . $item['image']) }}')">
                                        <img src="{{ asset('storage/thumbnail/' . $item['category_name'] . '/' . $item['thumb']) }}"
                                            alt="{{ $item['name'] }}" style="width: 100px; height: 100px;" />
                                    </a>
                                </td>
                                <td class="name text-center">{{ $item['name'] }}</td>
                                <td class="name text-center">{{ $item['qty'] }}</td>
                                <td class="category_name text-center">{{ $item['category_name'] }}</td>
                                <td class="price text-center">{{ number_format($item['price'], 2) }}</td>
                                <td class="price text-center">
                                    {{ \Carbon\Carbon::parse($item['updated_at'])->format('Y-m-d') }}</td>


                                <td class="stock_status text-center">
                                    <form action="{{ route('update_stock_status') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                        <select name="stock_status" class="form-select form-select-sm"
                                            style="background-color: {{ $item['stock_status'] == 'in_stock' ? '#d4edda' : '#f8d7da' }}; color: {{ $item['stock_status'] == 'in_stock' ? '#155724' : '#721c24' }};"
                                            onchange="this.form.submit()">
                                            <option value="in_stock"
                                                {{ $item['stock_status'] == 'in_stock' ? 'selected' : '' }}>
                                                IN STOCK
                                            </option>
                                            <option value="out_of_stock"
                                                {{ $item['stock_status'] == 'out_of_stock' ? 'selected' : '' }}>
                                                OUT OF STOCK
                                            </option>
                                        </select>
                                    </form>
                                </td>
                                <td class="status text-center">
                                    <form action="{{ route('update_status') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                        <select name="status" class="form-select form-select-sm"
                                            style="background-color: {{ $item['status'] == 'Active' ? '#d4edda' : '#f8d7da' }}; color: {{ $item['status'] == 'Active' ? '#155724' : '#721c24' }};"
                                            onchange="this.form.submit()">
                                            <option value="Active" {{ $item['status'] == 'Active' ? 'selected' : '' }}>
                                                ACTIVE
                                            </option>
                                            <option value="Inactive"
                                                {{ $item['status'] == 'Inactive' ? 'selected' : '' }}>
                                                INACTIVE
                                            </option>
                                        </select>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('edit_product') }}" method="GET" style="display:inline;">
                                        <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                        <button type="submit" class="btn btn-warning btn-sm content-icon">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-danger btn-sm content-icon"
                                        onclick="confirmDeletion('{{ $item['id'] }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm content-icon add-to-cart-btn"
                                        data-product-id="{{ $item['id'] }}">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $products->appends(['search' => request()->search, 'filter' => request()->filter])->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <!-- Modal Structure for Full Image View -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <!-- Full-Size Image Displayed in Modal -->
                        <img id="modalImage" src="" class="img-fluid rounded" alt="Full-Size Image">
                    </div>
                </div>
            </div>
        </div>




        <form action="{{ route('add-product') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal fade" id="addDealModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="addDealModal" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content bg-body-highlight p-6">
                        <div class="modal-header justify-content-between border-0 p-0 mb-2">
                            <h3 class="mb-0">Add Product Detail</h3>
                            <button type="reset" class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="modal"
                                aria-label="Close">
                                <span class="fas fa-times text-danger"></span>
                            </button>
                        </div>

                        <div class="modal-body px-0">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <input type="hidden" name="stock_status" value="in_stock">
                                    <div class="mb-4">
                                        <label class="text-body-highlight fw-bold mb-2">Select Product Category</label>
                                        <select class="form-select category-select" name="category_id"
                                            id="categorySelect">
                                            <option value="">Select</option>
                                            @foreach ($categories as $item)
                                                <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="text-body-highlight fw-bold mb-2">Design No</label>
                                        <input class="form-control" type="text" name="p_name"
                                            placeholder="Enter Product name" />
                                        @error('p_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="text-body-highlight fw-bold mb-2">Product Price</label>
                                        <input class="form-control" id="priceInput" type="text" name="price"
                                            placeholder="Enter Product price" />
                                        @error('price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="text-body-highlight fw-bold mb-2">Image</label>
                                        <div class="input-group">
                                            <input class="form-control" type="file" name="image" />
                                            <span class="input-group-text"><span
                                                    class="fas fa-image text-body-tertiary"></span></span>
                                        </div>
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="text-body-highlight fw-bold mb-2">Total Stock</label>
                                        <input class="form-control" type="number" id="totalStockInput" name='qty'
                                            placeholder="Enter Total Stock" min="0" required />
                                        @error('total_stock')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- <div class="mb-4">
                                        <label class="text-body-highlight fw-bold mb-2">Total Color</label>
                                        <input class="form-control" type="number" name="total_color"
                                            id="totalColorInput" placeholder="Enter Total Color Options" min="1"
                                            required />
                                        @error('total_color')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> --}}

                                </div>

                                <div class="col-lg-6">
                                    <div id="color-container" class="mb-3">
                                        <!-- Dynamically generated color input fields will be inserted here -->
                                    </div>
                                    <button type="button" class="btn btn-success mt-3" id="add-color">
                                        <i class="fas fa-plus-circle"></i> Add Color
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-0 pt-6 px-0 pb-0">
                            <button type="reset" class="btn btn-link text-danger px-3 my-0" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary my-0">Create Product</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
    

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // this.blur();
            const categorySelect = new Choices('.category-select', {
                // this.blur();
                searchEnabled: true, // Enables search functionality
                placeholderValue: 'Select a category', // Placeholder text
                searchPlaceholderValue: 'Search categories...' // Search input placeholder
            });
        });
    </script>

    <script>
        // Initialize cart from localStorage if available
        let cartProductIds = JSON.parse(localStorage.getItem('cart')) || [];

        // Add event listener to the Add to Cart button
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');

                // Check if the product ID is already in the array
                if (!cartProductIds.includes(productId)) {
                    cartProductIds.push(productId); // Add product ID to the array
                    localStorage.setItem('cart', JSON.stringify(cartProductIds)); // Save to localStorage
                    Swal.fire({
                        icon: 'success',
                        title: 'Product added to temporary cart!',
                        text: 'You can review your cart before proceeding to checkout.',
                        confirmButtonText: 'Ok'
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Product already in your cart!',
                        text: 'This product is already in your cart. You can proceed to checkout or continue shopping.',
                        confirmButtonText: 'Got it'
                    });

                }
            });
        });

        // Function to send the cart data to the backend
        function saveCartToDatabase() {
            if (cartProductIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No products in the cart!',
                    text: 'Please add some products to your cart before proceeding.',
                    confirmButtonText: 'Got it!'
                });
                return;
            }

            fetch('{{ route('save-cart') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_ids: cartProductIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        // SweetAlert for success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            confirmButtonText: 'Ok'

                        });
                        localStorage.removeItem('cart'); // Clear cart after successful save
                    } else {
                        // SweetAlert for error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message,
                        });
                    }
                })
                .catch(error => {
                    // SweetAlert for error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message,
                    });
                });
        }
    </script>

    <script>
        function confirmDeletion(itemId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, redirect to delete route
                    window.location.href = `delete_product/${itemId}`;
                }
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-open the modal if there are validation errors
            @if ($errors->any())
                var myModal = new bootstrap.Modal(document.getElementById('addDealModal'), {});
                myModal.show();
            @endif

            // Clear form fields and error messages when the modal is closed
            var productModal = document.getElementById('addDealModal');
            productModal.addEventListener('hide.bs.modal', function() {
                // Clear all input fields
                productModal.querySelectorAll('input').forEach(input => input.value = '');
                productModal.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

                // Remove error messages
                productModal.querySelectorAll('.text-danger').forEach(error => error.textContent = '');
            });
        });
    </script>

    <!-- JavaScript to Change Modal Image Source -->
    <script>
        function showImage(src) {
            document.getElementById('modalImage').src = src;
        }
    </script>

    <script>
        function editCategory(id) {
            // Redirect to edit page
            window.location.href = `/categories/${id}/edit`;
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('categorySelect');
            const priceInput = document.getElementById('priceInput');

            categorySelect.addEventListener('change', function() {
                this.blur();
                const selectedOption = this.options[this.selectedIndex];
                const categoryPrice = selectedOption.getAttribute('data-price');

                // Set the price input value to the selected category's price
                priceInput.value = categoryPrice || '';
                this.blur();
            });
        });
    </script>

    <!-- Script to handle the image display in modal -->
    <script>
        function showImage(imageUrl) {
            var modalImage = document.getElementById('modalImage');
            modalImage.src = imageUrl; // Set the full image URL to the modal image
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorContainer = document.getElementById('color-container');
            const addColorButton = document.getElementById('add-new-color');

            let colorIndex = 1; // Start index for color inputs

            // Add new color row
            addColorButton.addEventListener('click', function() {
                const colorRow = document.createElement('div');
                colorRow.classList.add('row', 'g-2', 'mb-3', 'color-item');
                colorRow.innerHTML = `
            <div class="col-6">
                <input type="text" name="colors[${colorIndex}][color_name]" class="form-control" placeholder="Color Name" required />
            </div>
            <div class="col-4">
                <input type="number" name="colors[${colorIndex}][quantity]" class="form-control" placeholder="Quantity" min="0" required />
            </div>
            <div class="col-2 text-center">
                <button type="button" class="btn btn-danger remove-color">
                    <i class="fas fa-minus-circle"></i>
                </button>
            </div>
        `;

                colorContainer.appendChild(colorRow);
                colorIndex++;
            });

            // Remove color row
            colorContainer.addEventListener('click', function(event) {
                if (event.target.closest('.remove-color')) {
                    const colorItem = event.target.closest('.color-item');
                    colorContainer.removeChild(colorItem);
                }
            });
        });
    </script>

    <script>
        document.getElementById('add-color').addEventListener('click', function() {
            const colorCount = document.querySelectorAll('.color-item').length;
            const colorContainer = document.getElementById('color-container');

            // Create a new color item
            const newColorItem = document.createElement('div');
            newColorItem.classList.add('row', 'g-3', 'mb-3', 'color-item');

            newColorItem.innerHTML = `
                        <div class="col-5">
                            <label for="color_select_${colorCount}" class="form-label">Select Color</label>
                            <select name="colors[${colorCount}][color_name]" id="color_select_${colorCount}" class="form-select color-select" required>
                                <option value="">Select Color</option>
                                @foreach ($colors as $color)
                                    <option value="{{ $color->color_name }}">{{ $color->color_name }}</option>
                                @endforeach
                                <option value="add_new">Add New Color</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="quantity_${colorCount}" class="form-label">Quantity</label>
                            <input type="number" id="quantity_${colorCount}" name="colors[${colorCount}][quantity]" class="form-control"
                                placeholder="Quantity" min="0" required />
                        </div>
                        <div class="col-12 mt-2 new-color-container d-none">
                            <label for="new_color_${colorCount}" class="form-label">Add New Color</label>
                            <input type="text" id="new_color_${colorCount}" name="colors[${colorCount}][new_color]" class="form-control new-color-input"
                                placeholder="Enter New Color" />
                        </div>
                        <div class="col-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-color mt-4">
                                <i class="fas fa-minus-circle"></i>
                            </button>
                        </div>
                    `;

            colorContainer.appendChild(newColorItem);

            // Initialize Choices.js for the dropdown
            const colorSelect = newColorItem.querySelector('.color-select');
            const choicesInstance = new Choices(colorSelect, {
                searchEnabled: true,
                removeItemButton: false,
                placeholder: true,
                placeholderValue: 'Select Color',
            });

            // Add event listener for the dropdown
            const newColorInputContainer = newColorItem.querySelector('.new-color-container');
            colorSelect.addEventListener('change', function() {
                if (colorSelect.value === 'add_new') {
                    newColorInputContainer.classList.remove('d-none');
                    // Set color_name to null by clearing the dropdown selection
                    colorSelect.name = "";
                } else {
                    newColorInputContainer.classList.add('d-none');
                    colorSelect.name = `colors[${colorCount}][color_name]`;
                }
            });
        });

        // Remove color item
        document.getElementById('color-container').addEventListener('click', function(e) {
            if (e.target.closest('.remove-color')) {
                e.target.closest('.color-item').remove();
            }
        });
    </script>

    <script>
        var colors = @json($colors); // Assuming $colors is an array of color data (objects)
        // dd(colors);
    </script>
    <script>
        document.getElementById('totalColorInput').addEventListener('input', function() {
            let totalColor = parseInt(this.value);
            let totalStock = parseInt(document.getElementById('totalStockInput')?.value || 0);

            if (totalColor > 0 && totalStock > 0) {
                let quantityPerColor = Math.floor(totalStock / totalColor); // Calculate the quantity per color

                // Clear any previous color fields
                let container = document.getElementById('color-fields-container');
                container.innerHTML = '';

                // Generate the color fields based on totalColor
                for (let i = 0; i < totalColor; i++) {
                    let colorField = document.createElement('div');
                    colorField.classList.add('row', 'g-4', 'mb-4'); // Add row and gap classes

                    // Create the color name dropdown with search functionality
                    let colorDropdown =
                        `<select class="form-select color-select" name="colors[${i}][color_name]" >`;
                    colorDropdown += '<option value="">Select Color</option>';

                    // Loop through each color from the `colors` data (assumed to be available in a JavaScript array)
                    for (let j = 0; j < colors.length; j++) {
                        colorDropdown += `<option value="${colors[j].color_name}">${colors[j].color_name}</option>`;
                    }

                    colorDropdown += '</select>';

                    // Add an input field for adding a new color if it doesn't exist
                    let newColorField = `
                <div class="col-12 mt-2 new-color-container" style="display:none;">
                    <label class="text-body-highlight fw-bold mb-2">Or Add New Color</label>
                    <input class="form-control new-color-input" type="text" name="colors[${i}][new_color]" placeholder="Enter New Color" />
                </div>
            `;

                    // Append the dropdown, quantity input, and the new color field
                    colorField.innerHTML = `
                <div class="col-6">
                    <label class="text-body-highlight fw-bold mb-2">Color ${i + 1} Name</label>
                    ${colorDropdown}
                </div>

                <div class="col-6">
                    <label class="text-body-highlight fw-bold mb-2">Quantity for Color ${i + 1}</label>
                    <input class="form-control" type="number" name="colors[${i}][quantity]" value="${quantityPerColor}"  />
                </div>

                ${newColorField}
            `;

                    // Append the color field to the container
                    container.appendChild(colorField);

                    // Initialize Choices.js for the dropdown
                    new Choices(colorField.querySelector('.color-select'), {
                        searchEnabled: true, // Enable search functionality
                        removeItemButton: true, // Optionally, show a remove button
                        placeholderValue: 'Select a color', // Placeholder text
                        itemSelectText: '', // Text to show when selecting an item
                    });

                    // Add event listener to detect if the user selects "Add New Color"
                    let colorSelect = colorField.querySelector('.color-select');
                    let newColorInputContainer = colorField.querySelector('.new-color-container');

                    // Initially hide the "Add New Color" input if a color is selected
                    if (colorSelect.value !== "") {
                        newColorInputContainer.style.display = 'none';
                    }

                    colorSelect.addEventListener('change', function() {
                        // If "Select Color" is selected, show the "Add New Color" input
                        if (colorSelect.value === "") {
                            newColorInputContainer.style.display =
                                'block'; // Show the "Add New Color" input
                        } else {
                            newColorInputContainer.style.display = 'none'; // Hide the "Add New Color" input
                        }
                    });
                }
            }
        });
    </script>
@endsection
