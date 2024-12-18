@extends('admin.layout.template')

@section('content')
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
    <style>
        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: #343a40 !important;
            /* Dark gray background */
            color: #ffffff !important;
            /* White text */
        }

        /* Apply on hover */
        select.form-select-sm:hover {
            background-color: #e2e6ea !important;
            /* Light grey hover effect */
        }

        /* Apply on focus */
        select.form-select-sm:focus {
            background-color: #fff3cd !important;
            /* Light yellow when focused */
            border-color: #ffeeba !important;
            color: #856404;
        }
    </style>
    <div class="container">
        <h2>Edit Product</h2>
        {{-- @dd($category); --}}

        <form action="{{ route('update_category') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Hidden input for product ID -->
            <input type="hidden" name="id" value="{{ $category->id }}">


            <!-- Product Price -->
            <div class="mb-3 col-md-3 mt-5">
                <label for="price" class="form-label">Category Price</label>
                <input type="text" class="form-control" id="price" name="price"
                    value="{{ old('price', $category->price) }}" required>
                @error('price')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            <!-- Submit and Cancel Buttons -->
            <div class="d-flex justify-content-start">
                <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
