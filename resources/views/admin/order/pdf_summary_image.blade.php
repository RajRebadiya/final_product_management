<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form with Product Images</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header,
        .footer {
            padding: 10px;
        }

        .header {
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
        }

        .header .company-info {
            width: 65%;
            float: left;
        }

        .header .company-info p {
            margin: 2px 0;
            line-height: 1.3;
        }

        .header .logo {
            float: right;
            width: 30%;
            text-align: right;
        }

        .header .logo img {
            max-width: 150px;
        }

        .header .gst-info {
            font-weight: bold;
            color: #d00;
        }

        .order-title {
            background-color: #f8d5d9;
            text-align: center;
            padding: 5px;
            font-weight: bold;
            margin: 10px 0;
        }

        .details-section {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
        }

        .details-section .left,
        .details-section .right {
            width: 48%;
        }

        .details-section p {
            margin: 3px 0;
            line-height: 1.4;
        }

        .details-section .title {
            font-weight: bold;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 20px;
        }

        .product-card {
            border: 1px solid #d00;
            padding: 10px;
            text-align: center;
            background-color: #f8f8f8;
            height: 350px;
            /* Fixed height for the product card */
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-card img {
            width: 100%;
            /* Fixed width */
            height: 150px;
            /* Fixed height */
            object-fit: cover;
            /* Ensures the image fits within the box */
            border: 1px solid #d00;
            margin-bottom: 10px;
        }

        .product-card .product-details {
            font-size: 14px;
        }

        .product-card .product-details strong {
            display: block;
            margin-top: 5px;
        }

        .print-button-container {
            text-align: center;
            margin-top: 20px;
        }

        .print-button {
            display: inline-block;
            background-color: #d00;
            color: #fff;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .print-button:hover {
            background-color: #a00;
        }

        .print-button::before {
            content: "🖨️ ";
        }

        /* Print styles */
        @media print {
            .product-grid {
                display: block;
                margin-top: 0px;
                /* Products will be displayed one by one */
            }

            .product-card {
                display: block;
                width: auto;
                /* Full width for each product */
                height: auto;
                margin-top: 0px;
                /* Allow cards to adjust height based on content */
                /* page-break-before: always; */
                /* Ensure each product starts on a new page */
                /* margin-bottom: 20px; */
                /* Space between products */
            }

            /* Ensure the product image is shown in its full size */
            .product-card img {
                width: 100%;
                /* Image will take full width of the card */
                height: 800px;
                /* Maintain original image height */
                border: none;
                margin-top: 0px;
                /* Remove border */
                /* margin-bottom: 10px; */
            }

            .product-details {
                font-size: 14px;
            }

            .print-button-container {
                display: none;
                /* Hide the print button in the printed version */
            }

            /* Optional: Add page breaks between product cards */
            .product-card {
                /* page-break-after: always; */
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-info">
            <p><strong>Sales Office 1:</strong> 201 to 203, Abhinandan Textile Market, Ring Road, Surat-395002</p>
            <p><strong>Sales Office 2:</strong> L-2654 to 57, Millennium Textile Market, Ring Road, Surat-395002, Ph.:
                2345859, 2365859</p>
            <p><strong>Godown:</strong> 301 to 309, 3rd Floor, Galaxy Icon Market, Opp. Raghukul Market, Ring Road,
                Surat-2. Ph.: 2345859, 2325859</p>
            <p class="gst-info">GSTIN: 24AATPR2907G1ZH</p>
        </div>
        <div class="logo">
            <img src="{{ asset('assets/img/favicons/veer_logo_1.png') }}" alt="Veer Creation Logo">
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="order-title">ORDER FORM WITH IMAGES</div>

    <div class="details-section">
        <div class="left" style="margin-left: 5%">
            <p class="title">M/s: {{ $orderArray['name'] }}</p>
            <p><strong>Add:</strong> {{ $orderArray['address'] }}</p>
            <p><strong>GSTIN:</strong> {{ $orderArray['gst_no'] }}</p>
            <p><strong>Agent:</strong> {{ $orderArray['agent'] }}</p>
            <p><strong>Haste:</strong> {{ $orderArray['haste'] }}</p>
        </div>
        <div class="right" style="margin-left: 25%">
            <p><strong>Order No:</strong> {{ $orderArray['order_number'] }}</p>
            <p><strong>Date:</strong> {{ $orderArray['order_date'] }}</p>
            <p><strong>Transport:</strong> {{ $orderArray['transport'] }}</p>
            <p><strong>Booking:</strong> {{ $orderArray['booking'] }}</p>
            <p><strong>Export:</strong> {{ $orderArray['export'] }}</p>
            <p><strong>Packing:</strong> {{ $orderArray['packing_bag'] }}</p>
        </div>
    </div>

    <div class="product-grid">
        @foreach ($orderArray['products'] as $product)
            <div class="product-card">
                <img src="{{ asset($product['image']) }}" alt="{{ $product['p_name'] }}">
                <div class="product-details d-flex">
                    <strong class='badge bg-dark text-light'>Design No: {{ $product['p_name'] }}</strong>
                    <p class='badge bg-dark text-light'>Quantity: {{ $product['buyQty'] }}</p>
                    <p class='badge bg-dark text-light'>Remark: {{ $product['remark'] }}</p>
                    <p class='badge bg-dark text-light'>Price: ₹{{ $product['price'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="print-button-container">
        <button class="print-button" onclick="window.print()">Print Order</button>
    </div>

</body>

</html>
