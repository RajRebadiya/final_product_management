<!-- resources/views/admin/order/offer_form_detail.blade.php -->
@extends('admin.layout.template')
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
            <div class="card-body">
                @if (!empty($order->products))
                    @php
                        // Group products by category
                        $groupedProducts = collect($order->products)->groupBy('category_name');
                    @endphp

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
                                            <li>Quantity: {{ $product['buyqty'] ?? 'N/A' }}</li>
                                            <li>Remark: {{ $product['remark'] ?? 'N/A' }}</li>
                                            <li>Price: {{ $product['price'] ?? 'N/A' }}</li>
                                        </ul>
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
            <a href="{{ route('download_summary', $order->order_number) }}" class="btn btn-primary">Download Summary
                PDF</a>
            {{-- <a href="#" onclick="window.print()" class="btn btn-secondary">Download Full PDF</a> --}}
        </div>
    </div>
@endsection
