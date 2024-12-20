<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@700&display=swap" rel="stylesheet">
    <title>Print QR Code</title>

    <style>
        body {
            font-family: "Lexend", serif;
            font-optical-sizing: auto;
            font-weight: 700;
            font-style: normal;


            margin: 0;
            padding: 0;
        }

        .barcode-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
            font-family: "Lexend", serif;
            font-optical-sizing: auto;
            font-weight: 700;
            font-style: normal;
        }

        .barcode-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            page-break-inside: avoid;
            border-radius: 10px;
            width: 168px;
            height: 188px;
            box-sizing: border-box;
            padding: 10px;
        }

        .left-content,
        .right-content {
            text-align: left;
            font-size: 12px;
        }

        .left-content p,
        .right-content p {
            margin: 5px 0;
        }

        .btn-print,
        .btn-preview {
            display: block;
            margin: 10px auto;
            padding: 10px 25px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 250px;
        }

        .btn-print-same {
            display: block;
            margin: 10px auto;
            padding: 10px 25px;
            background-color: #405cda;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 202px;
        }

        .btn-print:hover,
        .btn-preview:hover {
            background-color: #45a049;
        }



        @media print {
            body {
                font-family: "Lexend", serif;
                font-optical-sizing: auto;
                font-weight: 700;
                font-style: normal;
                margin: 0;
                padding: 0;
            }

            .quantity-input,
            .btn-print,
            .btn-print-same,
            .btn-preview,
            .product-info,
            .product-image {
                display: none;
            }

            .barcode-container {
                margin: 0;
            }

            #search-form {
                display: none;
            }

            .barcode-item {
                page-break-inside: avoid;
                height: 170px;
                width: 80%;

            }
        }
    </style>
</head>

<body>
    <form method="GET" id='search-form' action="{{ route('barcode2', ['id' => $product->p_name ?? null]) }}"
        class="mb-4" style="text-align: center; margin-bottom: 20px; margin-top: 2%;">
        <div class="input-group" style="max-width: 500px; margin: 0 auto; position: relative;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                placeholder="Search by Design No" aria-label="Search"
                style="border: 2px solid #4CAF50; border-radius: 25px; padding: 10px 15px; font-size: 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: 0.3s;width: 92%;">
            <button class="btn btn-primary" type="submit"
                style="position: absolute; right: 0; top: 0; height: 100%; border-radius: 25px; background-color: #4CAF50; color: white; border: none; padding: 0 20px; font-size: 16px; cursor: pointer; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: 0.3s;">
                Search
            </button>
        </div>
    </form>

    <div class="product-info"
        style="text-align: center; background-color: #f8f9fa; border: 1px solid #ddd; border-radius: 10px; padding: 20px; margin: 20px auto; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); width: 60%; max-width: 500px;">
        <!-- Product Image -->
        <div style="margin-bottom: 15px;">
            <img src="{{ asset('storage/images/' . $product->category_name . '/' . $product->image) }}"
                alt="Product Image"
                style="width: 200px; height: auto; object-fit: cover; border-radius: 10px; border: 2px solid black;">
        </div>
        <!-- Product Details -->
        <h2 style="color: #333; font-size: 1.8rem; margin-bottom: 10px;">
            {{ $product->name }}
        </h2>
        <p style="margin: 5px 0; color: #555; font-size: 1rem;">
            <strong style="color: #333;">Category:</strong> {{ $product->category_name }}
        </p>
        <p style="margin: 5px 0; color: #555; font-size: 1rem;">
            <strong style="color: #333;">Quantity:</strong> {{ $product->qty }}
        </p>
        <p style="margin: 5px 0; color: #555; font-size: 1rem;">
            <strong style="color: #333;">Price:</strong> ₹{{ number_format($product->price, 2) }}
        </p>
    </div>

    <!-- Print Button -->

    <!-- Barcode Stickers Container -->
    {{-- <div class="barcode-container" id="barcode-container">
        <!-- Stickers will be dynamically generated here -->
        <div class="barcode-item">
            <div style="text-align: center; padding: 5px;">
                <!-- Barcode -->
                {!! DNS1D::getBarcodeHTML($product->p_name, 'C128', 3, 40) !!}
                <!-- Design Number -->
                <p style="margin: 5px 0; font-weight: bold;">{{ $product->category_name . ' - ' . $product->p_name }}
                </p>
            </div>

            <div
                style="display: flex; flex-direction: row; width: 100%; padding: 5px; border: 2px solid #000; border-radius: px;">
                <!-- Left Side -->
                <div style="width: 50%; border-right: 1px solid #000; padding-right: 5px;">
                    <p style="margin: 5px 0; font-weight: bold;">Veer Creation</p>
                    <p style="margin: 5px 0; font-weight: bold;">{{ $product->category_name }}</p>
                </div>
                <!-- Right Side -->
                <div style="width: 50%; padding-left: 5px;">
                    <p style="margin: 5px 0; font-weight: bold;">D.No: {{ $product->p_name }}</p>
                    <p style="margin: 5px 0;  font-weight: bold;"><strong>MRP:</strong>
                        ₹{{ number_format($product->price, 2) }}</p>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="barcode-container" id="barcode-container">
        <div class="barcode-item"
            style="width: 200px;height: 103px;box-sizing: border-box;padding: 5px;margin-top: 15px; ">

            <!-- Product Category and Name -->
            <p style='margin: 0px; font-size: 18px; font-weight: 900;'>{{ $product->category_name }}</p>
            <p style="margin: 0px;margin-bottom: 3px; font-size: 18px; font-weight: 900;">D.No - {{ $product->p_name }}
            </p>

            <!-- Barcode Section -->
            <div>
                {!! DNS1D::getBarcodeHTML($product->p_name, 'C128', '2', '40') !!}
            </div>


        </div>
    </div>



    <button class="btn-print" onclick="window.print()">Print</button>
    <a class="btn-print-same" style='text-align: center;' href="{{ route('barcode') }}">Back To Product</a>

</body>

</html>
