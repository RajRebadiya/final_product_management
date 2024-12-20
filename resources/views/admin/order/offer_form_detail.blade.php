<!-- resources/views/admin/order/offer_form_detail.blade.php -->
@extends('admin.layout.template')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* Card Styling */
    .card {
        margin: 20px 0;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #f8f9fa;
        padding: 15px;
        border-bottom: 1px solid #ddd;
    }

    .card-body {
        padding: 20px;
    }

    /* Category Title Styling */
    .category-title {
        font-size: 18px;
        font-weight: bold;
        margin: 20px 0 10px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 5px;
    }

    /* Grid Layout for Products */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        /* Adjusted for larger items */
        gap: 20px;
    }

    .product-item {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .product-item h6 {
        font-size: 16px;
        margin-bottom: 10px;
        font-weight: bold;
    }

    /* Updated Image Size */
    .product-image {
        max-width: auto;
        /* Increased max width */
        max-height: 210px;
        /* Increased max height */
        margin: 10px auto;
        display: block;
    }

    .product-item ul {
        list-style: none;
        padding: 0;
        margin: 0;
        text-align: left;
    }

    .product-item ul li {
        font-size: 14px;
        margin-bottom: 5px;
    }

    @media print {

        /* Hide the print button in the print view */
        .print-btn {
            display: none;
        }

        /* Hide unnecessary elements like header, footer, and other non-essential elements */
        header,
        footer,
        .other-elements {
            display: none;
        }

        /* Layout adjustments for printing */
        .product-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .product-item {
            padding: 5px;
            border: none;
            text-align: center;
        }

        /* Image adjustments */
        .product-item img {
            max-width: 100%;
            /* Ensures image scales down while maintaining aspect ratio */
            max-height: 350px;
            /* Limits the maximum height */
            object-fit: contain;
            /* Keeps the image's aspect ratio intact */
            margin: 0 auto;
            /* Centers the image */
            display: block;
            /* Ensures image is displayed as a block element */
        }
    }
</style>


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


    <div class="container mt-4">
        <h2 class="text-center mb-4">Offer Form Detail</h2>

        <!-- Order Details Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Order Number: {{ $order->order_number }}</h4>
                <p>Date: {{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }}</p>
            </div>
            <div class="card-body">
                <h5>Customer Information</h5>
                <ul class="list-group">
                    <li class="list-group-item">Name: {{ $order->party_name }}</li>
                    <li class="list-group-item">Email: {{ $order->party_email }}</li>
                    <li class="list-group-item">Phone: {{ $order->party_mobile_no }}</li>
                    <li class="list-group-item">Address: {{ $order->party_address }}</li>
                    <li class="list-group-item">City: {{ $order->party_city }}</li>
                    <li class="list-group-item">GST No: {{ $order->party_gst_no }}</li>
                </ul>
            </div>
        </div>

        <!-- Products in the Order -->
        <div class="card">
            <div class="card-header">
                <h5>Product Details</h5>
            </div>
            {{-- @dd($order); --}}
            <!-- Updated Product Grid Section -->
            <div class="card-body">
                @if (!empty($order->products))
                    @php
                        // Group products by category
                        $groupedProducts = collect($order->products)->groupBy('category_name');
                    @endphp
                    @php
                        $user = Auth::guard('staff')->user();
                        $role = \App\Models\Role::where('id', $user->role_id)->first();
                        $permissions = $role->permissions;
                    @endphp

                    {{-- @dd($groupedProducts); --}}
                    @foreach ($groupedProducts as $category => $products)
                        <div class="category-group">
                            <h6 class="category-title">{{ $category }}</h6>
                            <div class="product-grid">
                                @foreach ($products as $product)
                                    {{-- @dd($product); --}}
                                    <div class="product-item">
                                        <h6>{{ $product['p_name'] ?? 'N/A' }}</h6>
                                        <img src="{{ $product['image'] ?? '' }}" alt="{{ $product['p_name'] ?? '' }}"
                                            class="img-fluid product-image">
                                        <ul>
                                            <li>Quantity: {{ $product['buyQty'] ?? 'N/A' }}</li>
                                            <li>Remark: {{ $product['remark'] ?? 'N/A' }}</li>
                                            <li>Price: {{ $product['price'] ?? 'N/A' }}</li>
                                        </ul>
                                        @if (!empty($permissions['Offer_Form']['update']) && $permissions['Offer_Form']['update'])
                                            <a href="{{ route('product.edit', ['order_number' => $order->order_number, 'product_id' => $product['product_id']]) }}"
                                                class="btn btn-warning btn-sm mt-2">Edit Product</a>
                                        @endif
                                        @if (!empty($permissions['Offer_Form']['delete']) && $permissions['Offer_Form']['delete'])
                                            <button class="btn btn-danger btn-sm mt-2"
                                                onclick="deleteProduct('{{ $order->order_number }}', '{{ $product['product_id'] }}')">Delete
                                                Product</button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No products found for this order.</p>
                @endif
            </div>

        </div>
        <!-- PDF Download Buttons -->
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('offer_form_download_summary', $order->order_number) }}" class="btn btn-primary mb-3">Summary
                PDF</a>
            <a href="{{ route('offer_form_download_summary_with_images', $order->order_number) }}"
                class="btn btn-primary mb-3">Summary
                PDF with Images</a>
            {{-- <a href="#" onclick="window.print()" class="btn btn-secondary">Download Full PDF</a> --}}
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteProduct(orderNumber, productId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the delete request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/product/delete/${orderNumber}/${productId}`;

                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);

                    // Add DELETE method
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

@endsection
