<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - {{ $order->order_number }}</title>
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .product-item {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .product-item img {
            max-width: 100px;
            max-height: 100px;
            object-fit: contain;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        ul li {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <h1>Order Details</h1>
    <p>Order Number: {{ $order->order_number }}</p>
    <p>Order Date: {{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }}</p>

    <h4>Customer Information:</h4>
    <ul>
        <li>Name: {{ $order->party_name }}</li>
        <li>Email: {{ $order->party_email }}</li>
        <li>Phone: {{ $order->party_mobile_no }}</li>
        <li>Address: {{ $order->party_address }}</li>
        <li>City: {{ $order->party_city }}</li>
        <li>GST No: {{ $order->party_gst_no }}</li>
    </ul>

    <h4>Product Details:</h4>
    <div class="product-grid">
        @foreach ($products as $product)
            <div class="product-item">
                <h6>{{ $product['p_name'] ?? 'N/A' }}</h6>
                <!-- Ensure the image URL is fully accessible -->
                {{-- <img src="{{ $product['image'] ?? '' }}" alt="{{ $product['p_name'] ?? 'N/A' }}"
                    class="img-fluid product-image"> --}}
                <ul>
                    <li>Quantity: {{ $product['buyqty'] ?? 'N/A' }}</li>
                    <li>Remark: {{ $product['remark'] ?? 'N/A' }}</li>
                    <li>Price: {{ $product['price'] ?? 'N/A' }}</li>
                </ul>
            </div>
        @endforeach
    </div>
</body>

</html>
