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
        :root {
            --primary-color: #007bff;
            /* Primary color */
            --secondary-color: #6c757d;
            /* Secondary color */
            --success-color: #28a745;
            /* Success color */
            --warning-color: #ffc107;
            /* Warning color */
            --danger-color: #dc3545;
            /* Danger color */
            --light-color: #f8f9fa;
            /* Light gray */
            --dark-color: #343a40;
            /* Dark gray */
            --hover-color: #e9ecef;
            /* Light gray for hover effects */
            --focus-color: #fff3cd;
            /* Light yellow for focus */
        }

        /* General Table Styles */
        .table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table-hover tbody tr:hover {
            background-color: var(--hover-color);
            /* Light gray hover */
            transition: background-color 0.3s ease;
            /* Smooth transition */
        }

        .table th,
        .table td {
            padding: 12px;
            vertical-align: middle;
            text-align: center;
            /* Center-align all content */
        }

        .table-light {
            background-color: var(--light-color);
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Pagination Styles */
        .pagination {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            justify-content: center;
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination li a,
        .pagination li span {
            display: block;
            padding: 8px 12px;
            text-decoration: none;
            background-color: var(--light-color);
            border: 1px solid var(--secondary-color);
            border-radius: 4px;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .pagination li.active span {
            background-color: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
        }

        .pagination li a:hover {
            background-color: var(--hover-color);
            color: #0056b3;
        }

        /* Button Styles */
        button.btn {
            height: 40px;
            line-height: 1.5;
            padding: 8px 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 80px;
            font-size: 0.875rem;
            border: 1px solid transparent;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        button i {
            width: 1em;
            height: 1em;
            display: inline-block;
        }

        button.btn:hover {
            background-color: var(--hover-color);
            border-color: var(--secondary-color);
            color: var(--dark-color);
        }

        button.btn-success {
            background-color: var(--success-color);
            color: #fff;
        }

        button.btn-success:hover {
            background-color: #218838;
            /* Slightly darker green */
        }

        button.btn-warning {
            background-color: var(--warning-color);
            color: #212529;
        }

        button.btn-warning:hover {
            background-color: #e0a800;
            /* Slightly darker yellow */
        }

        button.btn-danger {
            background-color: var(--danger-color);
            color: #fff;
        }

        button.btn-danger:hover {
            background-color: #c82333;
            /* Slightly darker red */
        }

        /* Dropdown Styling */
        select.form-select-sm {
            height: calc(2.25rem + 2px);
            background-color: var(--light-color);
            color: #495057;
            /* Dark text for readability */
            border: 1px solid var(--secondary-color);
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        select.form-select-sm:hover {
            background-color: var(--hover-color);
        }

        select.form-select-sm:focus {
            background-color: var(--focus-color);
            border-color: var(--warning-color);
            color: #856404;
            /* Match yellow theme for focus */
            box-shadow: 0 0 0 0.2rem rgba(255, 235, 186, 0.5);
        }

        /* Customize Dropdown Items (Choices.js) */
        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: var(--dark-color) !important;
            /* Dark gray for hover */
            color: #ffffff !important;
            /* White text */
        }

        /* Icons in Buttons */
        .content-icon i {
            font-size: 18px;
        }

        /* Row Interaction */
        .table-hover tbody tr {
            cursor: pointer;
        }

        /* Add-to-cart Button Styling */
        .add-to-cart-btn {
            background-color: var(--success-color);
            color: #fff;
        }

        .add-to-cart-btn:hover {
            background-color: #218838;
        }

        /* Enhanced Inputs (Optional) */
        .color-item {
            align-items: center;
            /* Vertically align fields */
        }

        .color-item .form-control,
        .color-item .form-select {
            height: calc(2.25rem + 2px);
            /* Match input and select height */
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


            <button id="saveOrderBtn" class="btn btn-primary" style="display:none;">Save Order</button>


            {{-- <button id="add-to-cart-btn" class="btn btn-primary me-4" onclick="saveCartToDatabase()">Save Cart</button> --}}

            @php
                $user = Auth::guard('staff')->user();
                $role = \App\Models\Role::where('id', $user->role_id)->first();
                $permissions = $role->permissions;
            @endphp






        </div>


        <form method="GET" action="{{ route('barcode') }}" class="mb-4">
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
            @php
                $user = Auth::guard('staff')->user()->email;
                // dd($user);
            @endphp
            <div class="table-responsive">
                <table class="table table-hover table-bordered  table-sm fs-9 mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Image</th>
                            <th class="text-center">Design No</th>
                            {{-- <th class="text-center">Total Stock</th> --}}
                            <th class="text-center">Category Name</th>
                            <th class="text-center">Price</th>
                            {{-- <th class="text-center">Date</th>
                            <th class="text-center">Stock Status</th> --}}
                            {{-- <th class="text-center">Bar Code</th> --}}
                            {{-- <th class="text-center">Status</th> --}}
                            {{-- @if (!empty($permissions['Product']['update']) && $permissions['Product']['update'])
                                <th class="text-center">Edit</th>
                            @endif
                            @if (!empty($permissions['Product']['delete']) && $permissions['Product']['delete'])
                                <th class="text-center">Delete</th>
                            @endif
                            <th class="text-center">Add to Cart</th> --}}
                            @if ($user == 'wb1@veer.com')
                                @if (!empty($permissions['Barcode']['create']) && $permissions['Barcode']['create'])
                                    <th class="text-center">Bar Code 1</th>
                                @endif
                            @elseif ($user == 'wb2@veer.com')
                                @if (!empty($permissions['Barcode']['create']) && $permissions['Barcode']['create'])
                                    <th class="text-center">Bar Code 1</th>
                                    <th class="text-center">Bar Code 2</th>
                                    <th class="text-center">Bar Code 3</th>
                                @endif
                            @elseif ($user == 'sb1@veer.com')
                                @if (!empty($permissions['Barcode']['create']) && $permissions['Barcode']['create'])
                                    <th class="text-center">Bar Code 5</th>
                                @endif
                            @elseif ($user == 'mb1@veer.com')
                                @if (!empty($permissions['Barcode']['create']) && $permissions['Barcode']['create'])
                                    <th class="text-center">Bar Code 1</th>
                                    <th class="text-center">Bar Code 2</th>
                                    <th class="text-center">Bar Code 3</th>
                                    {{-- Uncomment the line below if Barcode 4 is needed --}}
                                    {{-- <th class="text-center">Bar Code 4</th> --}}
                                    <th class="text-center">Bar Code 5</th>
                                @endif
                            @endif

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
                                {{-- <td class="name text-center">{{ $item['qty'] }}</td> --}}
                                <td class="category_name text-center">{{ $item['category_name'] }}</td>
                                <td class="price text-center">{{ number_format($item['price'], 2) }}</td>
                                {{-- <td class="price text-center">
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
                                </td> --}}
                                {{-- <td class="status text-center">
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
                                </td> --}}


                                <!-- Dynamic Barcode Display -->
                                {{-- <td class="barcode text-center">
                                    {!! DNS1D::getBarcodeHTML($item['name'], 'C128') !!}
                                    <small>{{ $item['name'] }}</small>
                                </td> --}}
                                {{-- @if (!empty($permissions['Product']['update']) && $permissions['Product']['update'])
                                    <td class="text-center">
                                        <form action="{{ route('edit_product') }}" method="GET" style="display:inline;">
                                            <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                            <button type="submit" class="btn btn-warning btn-sm content-icon">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                        </form>
                                    </td>
                                @endif
                                @if (!empty($permissions['Product']['delete']) && $permissions['Product']['delete'])
                                    <td class="text-center">
                                        <button class="btn btn-danger btn-sm content-icon"
                                            onclick="confirmDeletion('{{ $item['id'] }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                @endif
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm content-icon add-to-cart-btn"
                                        data-product-id="{{ $item['id'] }}">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                </td> --}}
                                @php
                                    // Define the barcodes and their corresponding print page functions
                                    $barcodes = [
                                        1 => [
                                            'src' => 'assets/img/barcode/barcode-1.png',
                                            'printFunction' => 'openPrintPage1',
                                        ],
                                        2 => [
                                            'src' => 'assets/img/barcode/barcode-2.png',
                                            'printFunction' => 'openPrintPage2',
                                        ],
                                        3 => [
                                            'src' => 'assets/img/barcode/barcode-3.png',
                                            'printFunction' => 'openPrintPage3',
                                        ],
                                        4 => [
                                            'src' => 'assets/img/barcode/barcode-4.png',
                                            'printFunction' => 'openPrintPage4',
                                        ],
                                        5 => [
                                            'src' => 'assets/img/barcode/barcode-4.png',
                                            'printFunction' => 'openPrintPage5',
                                        ],
                                    ];

                                    // Define user-specific barcode visibility
                                    $userBarcodeMapping = [
                                        'wb1@veer.com' => [1],
                                        'wb2@veer.com' => [1, 2, 3],
                                        'sb1@veer.com' => [5],
                                        'mb1@veer.com' => [1, 2, 3, 5],
                                        'sb2@veer.com' => [5],
                                    ];

                                    // Get the barcodes visible for the current user, or default to all barcodes
                                    $visibleBarcodes = $userBarcodeMapping[$user] ?? array_keys($barcodes);
                                @endphp

                                @foreach ($visibleBarcodes as $barcodeNumber)
                                    @if (!empty($permissions['Barcode']['create']) && $permissions['Barcode']['create'])
                                        <td class="text-center">
                                            <img style="margin-left: -22px; height: 108px;"
                                                src="{{ asset($barcodes[$barcodeNumber]['src']) }}"
                                                alt="Barcode {{ $barcodeNumber }}">
                                            <button style="display: block; margin-left: 75px; margin-top: 10px;"
                                                class="btn btn-primary btn-sm content-icon"
                                                onclick="{{ $barcodes[$barcodeNumber]['printFunction'] }}('{{ $item['id'] }}')">
                                                <i class="fa-solid fa-print"></i> Print
                                            </button>
                                        </td>
                                    @endif
                                @endforeach



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
        function openPrintPage1(productId) {
            // Redirect to the new route with the productId
            window.location.href = `/barcode1/${productId}`;
        }
    </script>
    <script>
        function openPrintPage2(productId) {
            // Redirect to the new route with the productId
            window.location.href = `/barcode2/${productId}`;
        }
    </script>
    <script>
        function openPrintPage3(productId) {
            // Redirect to the new route with the productId
            window.location.href = `/barcode3/${productId}`;
        }
    </script>
    <script>
        function openPrintPage4(productId) {
            // Redirect to the new route with the productId
            window.location.href = `/barcode4/${productId}`;
        }
    </script>
    <script>
        function openPrintPage5(productId) {
            // Redirect to the new route with the productId
            window.location.href = `/barcode5/${productId}`;
        }
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
    </script>


    <!-- JavaScript to Change Modal Image Source -->
    <script>
        function showImage(src) {
            document.getElementById('modalImage').src = src;
        }
    </script>



    <!-- Script to handle the image display in modal -->
    <script>
        function showImage(imageUrl) {
            var modalImage = document.getElementById('modalImage');
            modalImage.src = imageUrl; // Set the full image URL to the modal image
        }
    </script>
@endsection
