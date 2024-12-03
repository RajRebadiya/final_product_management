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

        input[type="date"] {
            pointer-events: auto;
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
        <h3>Create Offer Form</h3>
        <div class="d-flex mb-3" style="
        justify-content: end;
    ">

            <form id="cart-form" action="{{ route('add_to_cart') }}" method="POST">
                @csrf
                <input type="hidden" id="cart-product-ids" name="product_ids">
            </form>

            <button class="btn btn-primary me-4" type="button" data-bs-toggle="modal" data-bs-target="#addDealModal">
                <i class="fas fa-plus me-2"></i> Create Order Form
            </button>

        </div>

        <form method="GET" action="{{ route('offer_form') }}" class="mb-4">
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
                            <th class="text-center">Category Name</th>
                            <th class="text-center">Design No</th>
                            <th class="text-center">Total Stock</th>
                            <th class="text-center">Price</th>
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
                                <td class="category_name text-center">{{ $item['category_name'] }}</td>
                                <td class="name text-center">{{ $item['name'] }}</td>
                                <td class="name text-center">{{ $item['qty'] }}</td>
                                <td class="price text-center">{{ number_format($item['price'], 2) }}</td>
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
                                        <label class="text-body-highlight fw-bold mb-2">Select Existing Party</label>
                                        <select class="form-control" id="partyDropdown" data-choice>
                                            <option value="">Select Party</option>
                                            @foreach ($parties as $party)
                                                <option value="{{ $party->id }}" data-name="{{ $party->name }}"
                                                    data-email="{{ $party->email }}"
                                                    data-mobile_no="{{ $party->mobile_no }}"
                                                    data-address="{{ $party->address }}" data-city="{{ $party->city }}"
                                                    data-gst_no="{{ $party->gst_no }}">
                                                    {{ $party->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="mb-4">
                                        <label class="text-body-highlight fw-bold mb-2">Customer Name</label>
                                        <input class="form-control" type="text" name="name" id="customerName"
                                            placeholder="Enter Customer Name" />
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="text-body-highlight fw-bold mb-2">Email</label>
                                        <input class="form-control" type="email" name="email" id="email"
                                            placeholder="Email" />
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="text-body-highlight fw-bold mb-2">Mobile No</label>
                                        <input class="form-control" type="text" name="mobile_no" id="mobileNo"
                                            placeholder="Mobile No" />
                                        @error('mobile_no')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="text-body-highlight fw-bold mb-2">City</label>
                                        <input class="form-control" type="text" name="city" id="city"
                                            placeholder="City" />
                                        @error('city')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="text-body-highlight fw-bold mb-2">GST NO</label>
                                        <input class="form-control" type="text" name="gst_no" id="gstNo"
                                            placeholder="GST No" />
                                        @error('gst_no')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="text-body-highlight fw-bold mb-2">Address</label>
                                        <textarea class="form-control" name="address" id="address" placeholder="Address"></textarea>
                                        @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="text-body-highlight fw-bold mb-2">Order Date</label>
                                        <input class="form-control" type="date" style="cursor: pointer;"
                                            name="order_date" placeholder="Order Date" />
                                        @error('order_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="modal-footer border-0 pt-6 px-0 pb-0">
                            <button type="reset" class="btn btn-link text-danger px-3 my-0" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                            <button type="button" id="createProductBtn" class="btn btn-primary my-0">Create
                                Product</button>

                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
            // Check if cart is empty
            if (cartProductIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No products in the cart!',
                    text: 'Please add some products to your cart before proceeding.',
                    confirmButtonText: 'Got it!'
                });
                return;
            }

            // Collect form data from the modal
            const formData = {
                name: document.querySelector('input[name="name"]').value,
                email: document.querySelector('input[name="email"]').value,
                mobile_no: document.querySelector('input[name="mobile_no"]').value,
                city: document.querySelector('input[name="city"]').value,
                gst_no: document.querySelector('input[name="gst_no"]').value,
                address: document.querySelector('textarea[name="address"]').value,
                order_date: document.querySelector('input[name="order_date"]').value,
                stock_status: document.querySelector('input[name="stock_status"]').value
            };

            // Validate required fields
            if (!formData.name || !formData.email || !formData.mobile_no || !formData.city || !formData.order_date) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Required Fields',
                    text: 'Please fill in all the required fields.',
                });
                return;
            }

            // Add product IDs to the data
            formData.product_ids = cartProductIds;

            // Send data to the server
            fetch('{{ route('save-temp-order') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
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

                        // Close the modal
                        var productModal = document.getElementById('addDealModal');
                        var modal = bootstrap.Modal.getInstance(productModal);
                        modal.hide();

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
                        text: 'Something went wrong. Please try again.',
                    });
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
        document.getElementById('createProductBtn').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default form submission if inside a form
            saveCartToDatabase(); // Call your function
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
        document.getElementById('partyDropdown').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                // Populate form fields with selected party details
                document.getElementById('customerName').value = selectedOption.getAttribute('data-name');
                document.getElementById('email').value = selectedOption.getAttribute('data-email');
                document.getElementById('mobileNo').value = selectedOption.getAttribute('data-mobile_no');
                document.getElementById('city').value = selectedOption.getAttribute('data-city');
                document.getElementById('gstNo').value = selectedOption.getAttribute('data-gst_no');
                document.getElementById('address').value = selectedOption.getAttribute('data-address');
            } else {
                // Clear form fields if no party is selected
                document.getElementById('customerName').value = '';
                document.getElementById('email').value = '';
                document.getElementById('mobileNo').value = '';
                document.getElementById('city').value = '';
                document.getElementById('gstNo').value = '';
                document.getElementById('address').value = '';
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById('partyDropdown');
            new Choices(element, {
                searchEnabled: true, // Enables the search functionality
                shouldSort: false, // Prevents automatic sorting of options
                itemSelectText: '', // Removes default "Press Enter to select" text
            });
        });
    </script>
@endsection
