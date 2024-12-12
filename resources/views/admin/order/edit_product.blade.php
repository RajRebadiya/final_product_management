@extends('admin.layout.template')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h3 class='text-light'>Edit Product</h3>
                        <h6 class='text-light'>Order Number: <span class="">{{ $order->temp_order_number }}</span>
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form
                            action="{{ route('product.update', ['order_number' => $order->temp_order_number, 'product_id' => $product->id]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="productName" class="form-label">Product Name</label>
                                <input type="text" id="productName" class="form-control bg-light"
                                    value="{{ $order->p_name }}" readonly>
                            </div>

                            <div class="mb-4">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control"
                                    value="{{ $order->buyqty }}" required>
                            </div>

                            <div class="mb-4">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" name="price" id="price" class="form-control bg-light"
                                    value="{{ $product->price }}" readonly>
                            </div>

                            <div class="mb-4">
                                <label for="remark" class="form-label">Remark</label>
                                <textarea name="remark" id="remark" class="form-control" rows="3">{{ $order->remark }}</textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-lg px-5">Update Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
