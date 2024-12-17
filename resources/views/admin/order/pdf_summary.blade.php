<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
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

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .product-table th,
        .product-table td {
            border: 1px solid #d00;
            padding: 5px;
            text-align: center;

        }

        .product-table th {
            background-color: #f8d5d9;
        }

        .product-table td {
            text-align: left;

        }

        .product-table td:nth-child(1),
        .product-table td:nth-child(4),
        .product-table td:nth-child(5) {
            text-align: center;
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
            /* Add a print emoji */
        }

        /* Hide the print button when printing */
        @media print {
            .print-button-container {
                display: none;
            }
        }

        .footer {
            margin-top: 375px;
            padding: 10px;
            /* background-color: #d00; */
            color: black;
            text-align: center;
            font-size: 12px;
        }

        .footer .contact-info p {
            margin: 5px 0;
            color: black;
        }

        .footer .footer-notes {
            display: flex;
            justify-content: center;
            gap: 20px;
            font-weight: bold;
            margin-top: 10px;
        }

        .footer-notes span {
            padding: 5px 10px;
            background-color: #d00;
            color: #fff;
            /* border: 1px solid #fff; */
        }

        .footer-notes span:not(:last-child) {
            border-right: none;
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
            <img src="{{ asset('assets/img/favicons/veer_white.png') }}" alt="Veer Creation Logo">
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="order-title">ORDER FORM</div>

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

    <table class="product-table">
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Product</th>
                <th>Design No</th>
                <th>QTY</th>
                <th>Rate</th>
            </tr>
        </thead>
        <tbody>
            @php
                $categories = [];
            @endphp

            {{-- Group products by category --}}
            @foreach ($orderArray['products'] as $item)
                @php
                    // Group by category and aggregate data
                    $categories[$item['category_name']]['products'][] = [
                        'name' => $item['p_name'],
                        'qty' => $item['buyQty'],
                        'remark' => $item['remark'],
                    ];
                    $categories[$item['category_name']]['total_qty'] =
                        ($categories[$item['category_name']]['total_qty'] ?? 0) + $item['buyQty'];
                    $categories[$item['category_name']]['price'] = $item['price']; // Assuming uniform price per category
                @endphp
            @endforeach

            {{-- Render grouped data --}}
            @php $counter = 1; @endphp
            @foreach ($categories as $categoryName => $data)
                <tr>
                    <td>{{ $counter }}</td>
                    <td class="category_name font-weight-bold" style='font-weight: bold'>{{ $categoryName }}</td>
                    <td>
                        {{-- List products without remarks --}}
                        @foreach ($data['products'] as $product)
                            @if (!$product['remark'])
                                {{ $product['name'] }} - {{ $product['qty'] }},
                            @endif
                        @endforeach
                        <br>
                        {{-- List products with remarks --}}
                        @foreach ($data['products'] as $product)
                            @if ($product['remark'])
                                {{ $product['name'] }} ({{ $product['remark'] }}) - {{ $product['qty'] }},
                            @endif
                        @endforeach
                    </td>
                    <td>{{ $data['total_qty'] }}</td>
                    <td>{{ $data['price'] }}/-</td>
                </tr>
                @php $counter++; @endphp
            @endforeach
        </tbody>



    </table>

    <div class="print-button-container">
        <button class="print-button" onclick="window.print()">Print Order</button>
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="contact-info" style='float: left'>
            <p style="
            margin-left: -19px;
        ">Email: order.veercreation@gmail.com | <a
                    href="http://www.veercreation.com" target="_blank">www.veercreation.com</a></p>
            <p>Order Booking 📞 74055 29000 | Customer Care 📞 72848 80000</p>
        </div>
        <br><br>
        <div class="footer-notes" style="
        background-color: #dd0000;
        width: 100%;
    ">
            <span>NO LESS DHARA</span>
            <span>NO GOODS RETURN</span>
            <span>GST AS APPLICABLE</span>
        </div>
    </footer>

</body>

</html>
