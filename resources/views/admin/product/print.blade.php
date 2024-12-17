<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print QR Code</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .barcode-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        }

        .barcode-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            page-break-inside: avoid;
            /* border: 2px solid black; */
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

        .quantity-input {
            text-align: center;
            margin: 20px 0;
        }

        .quantity-input input {
            width: 80px;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }

        .quantity-input button {
            padding: 8px 15px;
            font-size: 16px;
            margin-left: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .quantity-input button:hover {
            background-color: #45a049;
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
        }

        .btn-print:hover,
        .btn-preview:hover {
            background-color: #45a049;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .quantity-input,
            .btn-print,
            .btn-preview,
            .product-info,
            .product-image {
                display: none;
            }

            .barcode-container {
                margin: 0;
            }

            .barcode-item {
                page-break-inside: avoid;
                height: 170px;
                width: 80%;
                /* margin-top: 10px; */
            }
        }
    </style>
</head>

<body>
    <div class="product-info"
        style="
    text-align: center;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    margin: 20px auto;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    width: 60%;
    max-width: 500px;">
        <!-- Product Image -->
        <div style="margin-bottom: 15px;">
            <img src="{{ asset('storage/images/' . $product->category_name . '/' . $product->image) }}"
                alt="Product Image"
                style="
            width: 200px;
            height: auto;
            object-fit: cover;
             border-radius: 10px; 
            border: 2px solid black;">
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

    <!-- Quantity Input and Buttons -->
    <div class="quantity-input">
        <label for="barcode-quantity">Enter Quantity for Stickers:</label>
        <input type="number" id="barcode-quantity" min="1" value="1" max="1">
        <button onclick="generateStickers()">Generate</button>
    </div>


    <button class="btn-print" onclick="window.print()">Print</button>



    <!-- Barcode Stickers Container -->
    <div class="barcode-container" id="barcode-container">
        <!-- Stickers will be dynamically generated here -->
    </div>

    <script>
        function generateStickers() {
            const quantity = document.getElementById('barcode-quantity').value;
            const container = document.getElementById('barcode-container');
            container.innerHTML = ''; // Clear previous stickers

            for (let i = 0; i < quantity; i++) {
                const stickerItem = document.createElement('div');
                stickerItem.classList.add('barcode-item');

                // Adding the sticker HTML structure
                stickerItem.innerHTML = `
                    <div style="text-align: center; padding: 5px;">
                        <!-- Barcode -->
                        {!! DNS1D::getBarcodeHTML($product->p_name, 'C128', 3, 40) !!}
                        <!-- Design Number -->
                        <p style="margin: 5px 0; font-weight: bold;">{{ $product->p_name }}</p>
                    </div>
                
                    <div style="display: flex; flex-direction: row; width: 100%; padding: 5px; border: 2px solid #000; border-radius: px;">
                        <!-- Left Side -->
                        <div style="width: 50%; border-right: 1px solid #000; padding-right: 5px;">
                            <p style="margin: 5px 0; font-weight: bold;">Veer Creation</p>
                             <p style="margin: 5px 0; font-weight: bold;">{{ $product->category_name }}</p>
                        </div>
                        <!-- Right Side -->
                        <div style="width: 50%; padding-left: 5px;">
                           
                             <p style="margin: 5px 0; font-weight: bold;">D.No: {{ $product->p_name }}</p>
                            <p style="margin: 5px 0;"><strong>MRP:</strong> ₹{{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>
                `;
                container.appendChild(stickerItem);
            }
        }

        function generatePreview() {
            generateStickers(); // Call generate function
            alert('Stickers are ready for preview. Click "Print" to proceed.');
        }
    </script>
</body>

</html>
