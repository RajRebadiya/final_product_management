<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.5;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        /* Details container using Flexbox for side-by-side layout */
        .details-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 30px;
            /* Space between the two boxes */
        }

        /* Individual boxes inside the details container */
        .detail-box {
            width: 48%;
            /* Ensures both boxes have equal width */
            padding: 15px;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .detail-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .detail-box ul li {
            margin-bottom: 10px;
        }

        .detail-box ul li strong {
            color: #333;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid #000;
            text-align: center;
            padding: 8px;
        }

        table th {
            background-color: #f4a460;
        }

        .table-heading {
            background-color: #ffa07a;
            font-weight: bold;
        }

        .table-row {
            text-align: left;
        }

        /* Responsive Design: Stack boxes vertically on smaller screens */
        @media (max-width: 768px) {
            .details-container {
                flex-direction: column;
                align-items: center;
            }

            .detail-box {
                width: 100%;
                /* Ensure full width on small screens */
                margin-bottom: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-title">OFFER FROM</div>
    </div>

    <!-- New details container with flexbox for side-by-side layout -->
    <div class="details-container">
        <div class="detail-box " style="text-align: left; width: 48%; display: inline-block; float: top;">
            <ul>
                <li><strong>Name:</strong> {{ $order->party_name }}</li>
                <li><strong>Address:</strong> {{ $order->party_address }}</li>
                <li><strong>City:</strong> {{ $order->party_city }}</li>
                <li><strong>Mobile No.:</strong> {{ $order->party_mobile_no }}</li>
                <li><strong>Email:</strong> {{ $order->party_email }}</li>
            </ul>
        </div>

        <div class="detail-box" style="text-align: left; width: 48%; float: right; overflow: hidden; height: 196px; ">
            <ul>
                <li><strong>Date:</strong> {{ $order->order_date }}</li>
                <li><strong>Invoice No.:</strong> {{ $order->order_number }}</li>
                <li><strong>GST:</strong> {{ $order->party_gst_no }}</li>
                <li><strong>Agent:</strong> {{ $order->party_agent }}</li>
                <li><strong>Transport:</strong> {{ $order->party_transport }}</li>
            </ul>
        </div>
    </div>

    <!-- Table -->
    <table class="table table-bordered " style='margin-top: 40%'>
        <thead>
            <tr class="table-heading">
                <th>No</th>
                <th>DESCRIPTION</th>
                <th>TOTAL DN.</th>
                <th>QTY SET</th>
                <th>RATE</th>
            </tr>
        </thead>
        <tbody>
            @php
                $categories = [];
            @endphp

            {{-- Group products by category --}}
            @foreach ($products as $item)
                @php
                    // Collect products and remarks
                    $categories[$item['category_name']]['products'][] = [
                        'name' => $item['p_name'],
                        'qty' => $item['buyqty'],
                        'remark' => $item['remark'],
                    ];
                    $categories[$item['category_name']]['total_qty'] =
                        ($categories[$item['category_name']]['total_qty'] ?? 0) + $item['buyqty'];
                    $categories[$item['category_name']]['price'] = $item['price']; // Assuming all items in a category have the same price
                @endphp
            @endforeach

            {{-- Render grouped data --}}
            @php $counter = 1; @endphp
            @foreach ($categories as $categoryName => $data)
                <tr>
                    <td>{{ $counter }}</td>
                    <td class="table-row">
                        <strong>{{ $categoryName }}</strong>
                        <br>
                        {{-- Show the product details without remarks first --}}
                        @foreach ($data['products'] as $product)
                            @if (!$product['remark'])
                                {{ $product['name'] }} - {{ $product['qty'] }},
                            @endif
                        @endforeach
                        <br>
                        {{-- Show the remarks inline, below the product list --}}
                        @foreach ($data['products'] as $product)
                            @if ($product['remark'])
                                {{ $product['name'] }} ({{ $product['remark'] }}) - {{ $product['qty'] }},
                            @endif
                        @endforeach
                    </td>
                    <td>{{ count($data['products']) }}</td> <!-- Total product count -->
                    <td>{{ $data['total_qty'] }}</td>
                    <td>{{ $data['price'] }}</td>
                </tr>
                @php $counter++; @endphp
            @endforeach
        </tbody>
    </table>
</body>

</html>
