@extends('admin.layout.template')
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    .card img {
        height: 100px;
        object-fit: cover;
    }

    .card-body {
        padding: 0.5rem;
    }

    .nav-tabs .nav-link {
        font-size: 14px;
    }

    .bg-light {
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .card-img-top {
        border-bottom: 1px solid #ddd;
    }

    .card-body .form-control {
        font-size: 14px;
        padding: 5px 10px;
    }

    .card-body button {
        margin-top: 10px;
        font-size: 14px;
    }

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

    .card-img-top {
        width: 100%;
        /* Ensures the image fills the card width */
        height: 150px;
        /* Fixed height for thumbnails */
        object-fit: cover;
        /* Crops or scales the image without distortion */
        border-bottom: 1px solid #ddd;
    }

    #modalImage {
        width: auto;
        /* Full width inside the modal */
        height: 700px;
        /* Maintain aspect ratio */
    }
</style>

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Choices.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <!-- Choices.js JS -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"> --}}
    <div class=" mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Create Offer Form</h1>
        </div>
        {{-- @dd($products); --}}
        <div class="row mt-4">
            <div class="col-md-8">
                <form method="GET" action="{{ route('new_offer_form') }}" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search by Design No or Category" aria-label="Search" />
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
                {{-- @dd($products); --}}

                <div class="row mt-3">
                    <!-- Product Items -->
                    @foreach ($products as $item)
                        <!-- Loop for each product -->
                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                            <div class="card">
                                <!-- Image with fixed dimensions -->
                                <!-- Image with Modal Trigger -->
                                <!-- Image with Modal Trigger -->
                                <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal"
                                    onclick="showImage('{{ asset('storage/images/' . $item['category_name'] . '/' . $item['image']) }}')">
                                    <img src="{{ asset('storage/thumbnail/' . $item['category_name'] . '/' . $item['thumb']) }}"
                                        alt="{{ $item['name'] }}" style="width: 233px; height: 150px; object-fit: fill;" />
                                </a>

                                <!-- Full-Screen Modal -->
                                <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-fullscreen">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="imageModalLabel">Product Image</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img id="modalImage" src="" class="img-fluid rounded"
                                                    alt="Full-Size Image">
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <!-- Product Name -->
                                <div class="card-body text-center"
                                    style="
                            padding-top: 4px;
                        ">
                                    <p class="card-text mb-2">{{ $item['category_name'] }} - {{ $item['name'] }}</p>

                                    <!-- Fields -->
                                    <div class="mb-2">
                                        <label for="buyqty_{{ $item['id'] }}" class="form-label">Buy Qty</label>
                                        <input type="number" name="buyqty[{{ $item['id'] }}]" class="form-control"
                                            id="buyqty_{{ $item['id'] }}" placeholder="Enter Quantity">
                                    </div>
                                    <div class="mb-2">
                                        <label for="remark_{{ $item['id'] }}" class="form-label">Remark</label>
                                        <input type="text" name="remark[{{ $item['id'] }}]" class="form-control"
                                            id="remark_{{ $item['id'] }}" placeholder="Enter Remark">
                                    </div>
                                    <button class="btn btn-primary w-100 add-to-cart-btn"
                                        data-product-id="{{ $item['id'] }}">Save Product</button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>


                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $products->appends(['search' => request()->search, 'filter' => request()->filter])->links('pagination::bootstrap-5') }}
                </div>
            </div>

            {{-- @dd($parties); --}}
            <div class="col-md-4">
                <form action="{{ route('add-product') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-body-highlight p-3">
                        <div class="mb-3">
                            <label class="text-body-highlight fw-bold">Select Existing Party</label>
                            <select class="form-control" id="partyDropdown" data-choice>
                                <option value="">Select Party</option>
                                @foreach ($parties as $party)
                                    <option value="{{ $party->id }}" data-name="{{ $party->name }}"
                                        data-email="{{ $party->email }}" data-mobile_no="{{ $party->mobile_no }}"
                                        data-address="{{ $party->address }}" data-city="{{ $party->city }}"
                                        data-gst_no="{{ $party->gst_no }}" data-agent="{{ $party->agent }}"
                                        data-transport="{{ $party->transport }}">
                                        {{ $party->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="text-body-highlight fw-bold">Customer Name</label>
                            <input class="form-control" id="customerName" type="text" name="name"
                                placeholder="Enter Customer Name" />
                        </div>
                        <div class="mb-3">
                            <label class="text-body-highlight fw-bold">Email</label>
                            <input class="form-control" id="email" type="email" name="email"
                                placeholder="Email" />
                        </div>
                        <div class="mb-3">
                            <label class="text-body-highlight fw-bold">Mobile No</label>
                            <input class="form-control" id="mobileNo" type="text" name="mobile_no"
                                placeholder="Mobile No" />
                        </div>
                        <div class="mb-3">
                            <label class="text-body-highlight fw-bold">City</label>
                            <input class="form-control" id="city" type="text" name="city"
                                placeholder="City" />
                        </div>
                        <div class="mb-3">
                            <label class="text-body-highlight fw-bold">GST NO</label>
                            <input class="form-control" id="gstNo" type="text" name="gst_no"
                                placeholder="GST No" />
                        </div>
                        <div class="mb-3">
                            <label class="text-body-highlight fw-bold">Address</label>
                            <textarea class="form-control" id="address" name="address" placeholder="Address"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="text-body-highlight fw-bold">Agent</label>
                            <input class="form-control" id="agent" type="text" name="agent"
                                placeholder="Agent No" />
                        </div>
                        <div class="mb-3">
                            <label class="text-body-highlight fw-bold">Transport</label>
                            <input class="form-control" id="transport" type="text" name="transport"
                                placeholder="Transport" />
                        </div>
                        <div class="mb-3">
                            <label class="text-body-highlight fw-bold">Order Date</label>
                            <input class="form-control" id="orderDate" type="date" name="order_date"
                                style="cursor: pointer;" />
                        </div>
                        <div class="border-0 pt-3">
                            <button type="reset" class="btn btn-link text-danger px-3 my-0"
                                aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary my-0 " id="createProductBtn">Create
                                Product</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function showImage(imageUrl) {
            document.getElementById('modalImage').src = imageUrl;
        }
    </script>

    <script>
        // Initialize cart from localStorage if available
        let cartProductIds = JSON.parse(localStorage.getItem('cart')) || [];
        let globalBuyqty = JSON.parse(localStorage.getItem('buyqty')) || {};
        let globalRemark = JSON.parse(localStorage.getItem('remark')) || {};

        // Add event listener to the Add to Cart button
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');

                // Check if the product ID is already in the array
                if (!cartProductIds.includes(productId)) {
                    cartProductIds.push(productId); // Add product ID to the array
                    localStorage.setItem('cart', JSON.stringify(cartProductIds)); // Save to localStorage

                    // Initialize buyqty and remark for this product if not set
                    if (!globalBuyqty[productId]) {
                        globalBuyqty[productId] = 1;
                    }
                    if (!globalRemark[productId]) {
                        globalRemark[productId] = '';
                    }

                    // Update localStorage with the new buyqty and remark
                    localStorage.setItem('buyqty', JSON.stringify(globalBuyqty));
                    localStorage.setItem('remark', JSON.stringify(globalRemark));

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

        // Function to update buyqty and remark when user changes inputs
        // Function to update buyqty and remark when user changes inputs
        function updateCartData() {
            // Iterate over each button that adds a product to the cart
            document.querySelectorAll('.add-to-cart-btn').forEach((button) => {
                const productId = button.getAttribute('data-product-id'); // Get the product ID

                // Get the corresponding quantity and remark values
                let qty = document.querySelector(`#buyqty_${productId}`).value;
                const productRemark = document.querySelector(`#remark_${productId}`).value;

                // If buyqty is not defined (empty), set it to 1 by default
                if (!qty || qty === '') {
                    qty = 1; // Default to 1 if no quantity is entered
                }

                // Update the global buyqty and remark objects with the new values
                globalBuyqty[productId] = qty;
                globalRemark[productId] = productRemark;

                // Store the updated values in localStorage
                localStorage.setItem('buyqty', JSON.stringify(globalBuyqty));
                localStorage.setItem('remark', JSON.stringify(globalRemark));

                // Debugging logs
                console.log('Captured Remark for Product ID ' + productId + ':', productRemark);
                console.log('Captured buyqty for Product ID ' + productId + ':', qty);
            });

            // // Debugging: Output the current state of the data
            // console.log('Global Buyqty:', globalBuyqty);
            // console.log('Global Remark:', globalRemark);
        }



        // Call updateCartData when the page is loaded
        document.addEventListener('DOMContentLoaded', () => {
            updateCartData();
        });

        // Save the cart data to the database
        function saveCartToDatabase() {

            console.log('Global Buyqty:', globalBuyqty); // Debugging globalBuyqty
            console.log('Global Remark:', globalRemark); // Debugging globalRemark
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
                agent: document.querySelector('input[name="agent"]').value,
                transport: document.querySelector('input[name="transport"]').value,
                buyqty: globalBuyqty, // Use the accumulated buyqty data
                remark: globalRemark, // Use the accumulated remark data
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
                        // Success
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            confirmButtonText: 'Ok'
                        });
                        localStorage.removeItem('cart'); // Clear cart after successful save
                        localStorage.removeItem('buyqty'); // Clear buyqty data
                        localStorage.removeItem('remark'); // Clear remark data
                    } else {
                        // Error
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message,
                        });
                    }
                })
                .catch(error => {
                    console.log('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.',
                    });
                });
        }

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
                document.getElementById('agent').value = selectedOption.getAttribute('data-agent');
                document.getElementById('transport').value = selectedOption.getAttribute('data-transport');
            } else {
                // Clear form fields if no party is selected
                document.getElementById('customerName').value = '';
                document.getElementById('email').value = '';
                document.getElementById('mobileNo').value = '';
                document.getElementById('city').value = '';
                document.getElementById('gstNo').value = '';
                document.getElementById('address').value = '';
                document.getElementById('agent').value = '';
                document.getElementById('transport').value = '';
            }
        });

        document.getElementById('createProductBtn').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default form submission if inside a form
            saveCartToDatabase(); // Call your function
        });

        const element = document.getElementById('partyDropdown');
        new Choices(element, {
            searchEnabled: true, // Enables the search functionality
            shouldSort: false, // Prevents automatic sorting of options
            itemSelectText: '', // Removes default "Press Enter to select" text
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Trigger updateCartData when the quantity or remark input is changed
            document.querySelectorAll('.form-control').forEach((input) => {
                input.addEventListener('input', updateCartData);
            });

            // Initial update to capture any values that might have been pre-filled
            updateCartData();

            console.log('Global Remark:', globalRemark); // Verify the structure and values

        });
    </script>
@endsection
