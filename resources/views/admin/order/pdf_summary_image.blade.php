<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form with Images</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            border-bottom: 2px solid #000;
            padding: 10px;
        }

        .header .company-info {
            width: 70%;
            float: left;
        }

        .header .logo {
            width: 30%;
            text-align: right;
            float: right;
        }

        .header .logo img {
            max-width: 150px;
        }

        .order-title {
            text-align: center;
            background-color: #f8d5d9;
            padding: 5px;
            font-weight: bold;
            margin: 10px 0;
        }

        .details-section {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
        }

        .details-section p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th,
        table td {
            border: 1px solid #d00;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #f8d5d9;
            font-weight: bold;
        }

        table img {
            max-width: 100px;
            height: auto;
            object-fit: contain;
            /* border: 1px solid #d00; */
        }

        .print-button-container {
            text-align: center;
            margin: 20px 0;
        }

        .print-button {
            background-color: #d00;
            color: #fff;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .print-button:hover {
            background-color: #a00;
        }

        @media print {
            .print-button-container {
                display: none;
            }
        }

        .footer {
            margin-top: 20px;
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
            <img src="{{ asset('assets/img/favicons/veer_logo_1.png') }}" alt="Veer Creation Logo">
        </div>
        <div style="clear: both;"></div>
    </div>


    <div class="order-title">ORDER FORM WITH IMAGES</div>

    <div class="details-section">
        <div class="left" style="margin-left: 5%">
            <p class="title"><strong>M/s:</strong> {{ $orderArray['name'] }}</p>
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
    <table>
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Product</th>
                <th>Design No</th>
                <th>QTY</th>
                <th>Rate</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderArray['products'] as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product['category_name'] }}</td>
                    <td>{{ $product['p_name'] }}</td>
                    <td>{{ $product['buyQty'] }}</td>
                    <td>₹{{ $product['price'] }}</td>
                    <td><img src="{{ asset($product['image']) }}" alt="{{ $product['p_name'] }}" width="100px"
                            height="100px"
                            style="
                            height: 150px;
                            width: 150px;
                        ">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <footer class="footer">
        <div class="contact-info" style="float: left; display: flex; align-items: center;">
            <!-- QR Code Section -->
            <div style="margin-right: 15px;">
                {!! DNS2D::getBarcodeHTML($order_number, 'QRCODE', 2, 2) !!}
            </div>

            <!-- Contact Information -->
            <div>
                <p style="margin: 0px;margin-left: -18px;">
                    Email: order.veercreation@gmail.com |
                    <a href="http://www.veercreation.com" target="_blank">www.veercreation.com</a>
                </p>
                <p style="margin: 0;">Order Booking 📞 74055 29000 | Customer Care 📞 72848 80000</p>
            </div>
        </div>

        <!-- Footer Notes -->
        <div class="footer-notes"
            style="background-color: #dd0000; width: 100%; text-align: center; color: white; padding: 10px 0; margin-top: 20px;">
            <span style="margin-right: 20px;">NO LESS DHARA</span>
            <span style="margin-right: 20px;">NO GOODS RETURN</span>
            <span>GST AS APPLICABLE</span>
        </div>
    </footer>
    <div class="print-button-container">
        <button class="print-button" onclick="window.print()">Print Order</button>
    </div>
</body>

</html>
