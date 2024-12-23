<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@700&display=swap" rel="stylesheet">

    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


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
            /* margin: 20px 0; */
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
            margin-top: 70px;
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

        #search-form .form-control:focus {
            border-color: #45a049;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
            outline: none;
        }

        #search-form .btn-primary:hover {
            background-color: #45a049;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
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
            .product-image,
            #percentage,
            .font_btn,
            .per {
                display: none;
            }

            #font-controls {
                display: none;
            }

            #search-form {
                display: none;
            }

            .barcode-container {
                margin: 0;
            }

            .barcode-item {
                page-break-inside: avoid;
                font-family: "Lexend", serif;
                font-optical-sizing: auto;
                font-weight: 700;
                font-style: normal;
            }
        }
    </style>
</head>

<body>

    @if (session('success'))
        <div class="alert alert-secondary alert-dismissible fade show " role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show " role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                aria-label="Close"></button>
        </div>
    @endif

    <form method="GET" id='search-form' action="{{ route('barcode5', ['id' => $product->p_name ?? null]) }}"
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




    <!-- Product Info Section -->
    @if ($product)
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

        <!-- Barcode Stickers Container -->
        <div class="barcode-container" id="barcode-container">
            <div class="barcode-item" style="width: 200px; height: 119px; box-sizing: border-box; margin-top: -6px;">
                <!-- Barcode Section -->
                <div style="text-align: center;">
                    {!! DNS1D::getBarcodeHTML($product->p_name, 'C128', 2, 40) !!}
                </div>

                <!-- Font Size Controls (Moved Outside and Side-by-Side) -->
                <div id="text-container" style="display: flex;">
                    <div style="text-align: center; font-weight: 900; padding: 2px; font-size: 18px;">
                        {{ $product->category_name . ' - ' }}
                    </div>
                    <div style="text-align: center; font-weight: 900; padding: 2px; font-size: 18px;">
                        {{ $product->p_name }}
                    </div>
                </div>

                <!-- Bottom Section: Veer and MRP -->
                <div style="display: flex; border: 2px solid #000; width: 89%; margin-top: -3px;">
                    <div
                        style="width: 50%; text-align: center; border-right: 2px solid #000; font-weight: 900; padding: 5px;">
                        <img src="{{ asset('assets/img/favicons/veer_red.png') }}" alt="Veer Logo"
                            style="height: 30px;">
                    </div>
                    <div style="text-align: center; font-weight: bold;">
                        RATE ₹<span id="updated-price">{{ $product->price }}</span>
                    </div>
                </div>

                <div id='font-controls' style="text-align: center; margin-top: 10px;">
                    <button class='font_btn btn btn-danger btn-sm' onclick="adjustFontSize('decrease')"
                        style="padding: 5px 10px;margin-right: 5px;display: inline-block;background: red;color: white;border: 2px solid white;border-radius: 8px;">-</button>
                    <button class='font_btn btn btn-success btn-sm' onclick="adjustFontSize('increase')"
                        style="padding: 5px 10px;margin-right: 5px;display: inline-block;background: green;color: white;border: 2px solid white;border-radius: 8px;"">+</button>
                </div>
            </div>
        </div>

        <button class="btn-print" onclick="window.print()">Print</button>
        <a class="btn-print-same" style='text-align: center;' href="{{ route('barcode') }}">Back To Product</a>
    @else
        <p style="text-align: center; font-size: 1.2rem; color: #555;">No product found for the search query.</p>
    @endif

    <script>
        // JavaScript to adjust font size dynamically
        function adjustFontSize(action) {
            // Get the text container
            const textContainer = document.getElementById('text-container');
            // Get all child divs inside the container
            const textElements = textContainer.querySelectorAll('div');

            // Loop through all child divs
            textElements.forEach(element => {
                // Get the current font size
                let currentFontSize = parseFloat(window.getComputedStyle(element).fontSize);

                // Adjust the font size based on the action
                if (action === 'increase') {
                    currentFontSize += 2;
                } else if (action === 'decrease' && currentFontSize > 2) {
                    currentFontSize -= 2;
                }

                // Apply the new font size
                element.style.fontSize = currentFontSize + 'px';
            });
        }
    </script>
</body>


</html>
