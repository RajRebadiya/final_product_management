@extends('admin.layout.template')

@section('content')

    <body>

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
        {{-- @dd($products); --}}
        <div class="cart">
            <h3>Your Cart</h3>
            <div class="cart-actions my-3" style='display:inline;'>
                <button type="submit" href="#" onclick="clearCart()" class="btn btn-danger">Clear Cart</button>
            </div>

            <a href="#" class="btn btn-success my-3" onclick="generatePDF()">Download as PDF</a>
            <div class="printable-area">
                @foreach ($products as $product)
                    <div class="product">
                        <img src="{{ asset('storage/images/' . $product['category_name'] . '/' . $product['image']) }}"
                            alt="{{ $product['p_name'] }}" style="width: 700px; height: 850px;">
                        <div class="product-name">{{ $product['p_name'] }}</div>
                    </div>
                @endforeach
            </div>

            <!-- Button to Clear Cart -->
            {{-- <form action="{{ route('clear_cart_product') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Clear Cart</button>
            </form> --}}


        </div>
    </body>


    <script>
        function generatePDF() {
            // Hide everything except the content to be printed
            const body = document.body;
            const printContent = document.querySelector('.printable-area').innerHTML;

            // Backup original content
            const originalContent = body.innerHTML;

            // Replace body content with printable content
            body.innerHTML = printContent;

            // Trigger print
            window.print();

            // Restore original content
            body.innerHTML = originalContent;
            window.location.reload(); // Reload to restore event listeners
        }

        function clearCart() {
            // Redirect to the clear cart route
            window.location.href = "{{ route('clear_cart_product') }}";
        }
    </script>
@endsection
