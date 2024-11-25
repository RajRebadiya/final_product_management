@extends('admin.layout.template')
@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Choices.js CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
        <!-- Choices.js JS -->
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>



    <style>
    
            /* Darken the hover effect for dropdown options */
        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: #343a40 !important; /* Dark gray background */
            color: #ffffff !important; /* White text */
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
                margin: 0; /* Remove default margin */
                padding: 0; /* Remove default padding */
                list-style: none; /* Remove bullet points */
                display: flex; /* Align pagination links inline */
                justify-content: center; /* Center the links */
            }
            
            .pagination li {
                margin: 0 5px; /* Add spacing between links */
            }
            
            .pagination li a,
            .pagination li span {
                display: block;
                padding: 8px 12px; /* Adjust padding for better click area */
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

.table th, .table td {
    padding: 12px;
    vertical-align: middle;
}

.table-light {
    background-color: #f8f9fa;
}

.content-icon i {
    font-size: 18px;
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




    <div class="mb-6">
        <h3>Products List</h3>
        <div class="d-flex mb-3" style="
        justify-content: end;
    ">

            <form id="cart-form" action="{{ route('add_to_cart') }}" method="POST">
                @csrf
                <input type="hidden" id="cart-product-ids" name="product_ids">
            </form>


            {{-- <div class="d-flex flex-between-center mb-3 search-box navbar-top-search-box d-none d-lg-block"
                style="width:25rem;">
                <form class="position-relative" id="searchForm" data-bs-toggle="search" data-bs-display="static">
                    <input id="searchInput" class="form-control search-input fuzzy-search rounded-pill form-control-sm"
                        type="search" placeholder="Search..." aria-label="Search" />
                    <span class="fas fa-search search-box-icon"></span>
                </form>
                <div class="btn-close position-absolute end-0 top-50 translate-middle cursor-pointer shadow-none"
                    data-bs-dismiss="search">
                    <button class="btn btn-link p-0" aria-label="Close"></button>
                </div>
            </div> --}}

            <!-- Results will be displayed here -->
            {{-- <ul id="products-container"></ul> --}}
            <button id="add-to-cart-btn" class="btn btn-primary me-4">Add to Cart</button>
            <button id="cart" class="btn btn-primary me-4" onclick="window.location='{{ route('cart_detail') }}'">Cart</button>

           <button class="btn btn-primary me-4" type="button" data-bs-toggle="modal" data-bs-target="#addDealModal">
                <i class="fas fa-plus me-2"></i> Add New Product
            </button>
          <div class="d-flex justify-content-between mb-3">
            <div class="btn-group">
                <button class="btn btn-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter me-2"></i> Filter Products
                </button>
                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                    <li><a class="dropdown-item filter-option" href="{{ route('products.filter', ['filter' => 'p_new_to_old']) }}">Product: New to Old</a></li>
                    <li><a class="dropdown-item filter-option" href="{{ route('products.filter', ['filter' => 'p_old_to_new']) }}">Product: Old to New</a></li>
                    <li><a class="dropdown-item filter-option" href="{{ route('products.filter', ['filter' => 'price_low_to_high']) }}">Price: Low to High</a></li>
                    <li><a class="dropdown-item filter-option" href="{{ route('products.filter', ['filter' => 'price_high_to_low']) }}">Price: High to Low</a></li>
                    <li><a class="dropdown-item filter-option" href="{{ route('products.filter', ['filter' => 'c_new_to_old']) }}">Category: New to Old</a></li>
                    <li><a class="dropdown-item filter-option" href="{{ route('products.filter', ['filter' => 'c_old_to_new']) }}">Category: Old to New</a></li>
                    <li><a class="dropdown-item filter-option" href="{{ route('products.filter', ['filter' => 'c_a_to_z']) }}">Category: A to Z</a></li>
                    <li><a class="dropdown-item filter-option" href="{{ route('products.filter', ['filter' => 'c_z_to_a']) }}">Category: Z to A</a></li>
                    <li><a class="dropdown-item filter-option" href="{{ route('products.filter', ['filter' => 'latest_updated']) }}">Latest Updated Product</a></li>
                </ul>
            </div>
        </div>

        </div>

        
        <form method="GET" action="{{ route('dashboard_2') }}" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by Design No or Category" aria-label="Search" />
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
                        <td class="no text-center">{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                        <td class="image text-center">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal"
                               onclick="showImage('{{ asset('storage/images/' . $item['category_name'] . '/' . $item['image']) }}')">
                                <img src="{{ asset('storage/thumbnail/' . $item['category_name'] . '/' . $item['thumb']) }}"
                                     alt="{{ $item['name'] }}"  style="width: 100px; height: 100px;"/>
                            </a>
                        </td>
                        <td class="name text-center">{{ $item['name'] }}</td>
                        <td class="category_name text-center">{{ $item['category_name'] }}</td>
                        <td class="price text-center">{{ number_format($item['price'], 2) }}</td>
                          <td class="price text-center">{{ \Carbon\Carbon::parse($item['updated_at'])->format('Y-m-d') }}</td>


                        <td class="stock_status text-center">
                            <form action="{{ route('update_stock_status') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                <select name="stock_status" class="form-select form-select-sm"
                                        style="background-color: {{ $item['stock_status'] == 'in_stock' ? '#d4edda' : '#f8d7da' }}; color: {{ $item['stock_status'] == 'in_stock' ? '#155724' : '#721c24' }};"
                                        onchange="this.form.submit()">
                                    <option value="in_stock" {{ $item['stock_status'] == 'in_stock' ? 'selected' : '' }}>
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
                                    <option value="Inactive" {{ $item['status'] == 'Inactive' ? 'selected' : '' }}>
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
                            <button class="btn btn-danger btn-sm content-icon" onclick="confirmDeletion('{{ $item['id'] }}')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('add_to_cart') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                <button type="submit" class="btn btn-success btn-sm content-icon">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </form>
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
                        <h3 class="mb-0">Add Product Detail</h3><button type="reset"
                            class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="modal" aria-label="Close"><span
                                class="fas fa-times text-danger"></span></button>
                    </div>
             
                    <div class="modal-body px-0">
                        <div class="row g-4">
                            <div class="col-lg-6">

                                <input type="hidden" name="stock_status" value="in_stock">
                              <div class="mb-4">
                                <label class="text-body-highlight fw-bold mb-2">Select Product Category</label>
                                <select class="form-select category-select" name="category_id"  id="categorySelect">
                                    <option value="">Select</option>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}" data-price="{{ $item->price }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                                <div class="mb-4"><label class="text-body-highlight fw-bold mb-2">Desing No</label><input class="form-control" type="text" name='p_name'
                                        placeholder="Enter Product name" />
                                    @error('p_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                 <div class="mb-4"><label class="text-body-highlight fw-bold mb-2">Product
                                        price</label><input class="form-control"  id="priceInput"  type="text" name='price'
                                        placeholder="Enter Product price" />
                                    @error('price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4"><label class="text-body-highlight fw-bold mb-2">Image</label>
                                    <div class="input-group"><input class="form-control" type="file"
                                            placeholder="Enter image url" name='image' /><span
                                            class="input-group-text"><span
                                                class="fas fa-image text-body-tertiary"></span></span>
                                    </div>
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>

                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-6 px-0 pb-0"><button type="reset"
                            class="btn btn-link text-danger px-3 my-0" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button><button type="submit" class="btn btn-primary my-0">Create
                            Product</button></div>
                </div>
    </form>
    </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
   <script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = new Choices('.category-select', {
            searchEnabled: true, // Enables search functionality
            placeholderValue: 'Select a category', // Placeholder text
            searchPlaceholderValue: 'Search categories...' // Search input placeholder
        });
    });
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
        
            // Add to cart button functionality
            document.getElementById('add-to-cart-btn').addEventListener('click', function() {
                if (selectedProductIds.length > 0) {
                    document.getElementById('cart-product-ids').value = JSON.stringify(selectedProductIds);
                    document.getElementById('cart-form').submit();
                } else {
                    alert('Please select at least one product to add to the cart.');
                }
            });

            // Print selected products
            document.getElementById('print-selected-btn').addEventListener('click', function() {
                if (selectedProductIds.length === 0) {
                    alert('No products selected!');
                    return;
                }

                // Prepare printable content
                const printableArea = document.createElement('div');
                selectedProductIds.forEach((productId) => {
                    const row = document.querySelector(`[data-product-id="${productId}"]`).closest(
                        'tr').cloneNode(true);
                    row.querySelector('td:first-child').remove(); // Remove the checkbox column
                    printableArea.appendChild(row);
                });

                // Backup original content
                const originalContent = document.body.innerHTML;

                // Set printable content
                document.body.innerHTML = `<table class="table">${printableArea.innerHTML}</table>`;

                // Trigger print
                window.print();

                // Restore original content
                document.body.innerHTML = originalContent;
                window.location.reload(); // Reload to restore event listeners
            });

            // Restore selections when the page is loaded
            restoreSelections();
        });
        
        document.addEventListener('DOMContentLoaded', function () {
            const categorySelect = document.getElementById('categorySelect');
            const priceInput = document.getElementById('priceInput');
        
            categorySelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const categoryPrice = selectedOption.getAttribute('data-price');
        
                // Set the price input value to the selected category's price
                priceInput.value = categoryPrice || '';
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
@endsection
