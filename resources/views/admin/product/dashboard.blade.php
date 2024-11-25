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



    <div class="mb-6">
        <div class="d-flex flex-between-center mb-3">
            <h3>Products List</h3>
            <div class="d-flex flex-between-center mb-3 search-box navbar-top-search-box d-none d-lg-block"
                style="width:25rem;">
                <form class="position-relative" id="searchForm" data-bs-toggle="search" data-bs-display="static">
                    <input id="searchInput" class="form-control search-input fuzzy-search rounded-pill form-control-sm"
                        type="search" placeholder="Search..." aria-label="Search" />
                    <span class="fas fa-search search-box-icon"></span>
                </form>
                <div class="btn-close position-absolute end-0 top-50 translate-middle cursor-pointer shadow-none"
                    data-bs-dismiss="search">
                    <button class="btn btn-link p-0" aria-label="Close"></button>
                </div>
            </div>

            <!-- Results will be displayed here -->
            {{-- <ul id="products-container"></ul> --}}

            <button class="btn btn-primary me-4" type="button" data-bs-toggle="modal" data-bs-target="#addDealModal"
                aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><svg
                    class="svg-inline--fa fa-plus me-2" aria-hidden="true" focusable="false" data-prefix="fas"
                    data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                    data-fa-i2svg="">
                    <path fill="currentColor"
                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z">
                    </path>
                </svg><!-- <span class="fas fa-plus me-2"></span> Font Awesome fontawesome.com -->Add New Product</button>
        </div>

        <div class="row d-flex" id='main_data'>
            {{-- <div class="col-md-4"> --}}
            @foreach ($products as $item)
                <div class="swiper-theme-container products-slider d-flex col-md-3">
                    <div class="swiper theme-slider swiper-initialized swiper-horizontal mb-3 swiper-backface-hidden"
                        data-swiper="{&quot;slidesPerView&quot;:1,&quot;spaceBetween&quot;:16,&quot;breakpoints&quot;:{&quot;450&quot;:{&quot;slidesPerView&quot;:2,&quot;spaceBetween&quot;:16},&quot;576&quot;:{&quot;slidesPerView&quot;:3,&quot;spaceBetween&quot;:20},&quot;768&quot;:{&quot;slidesPerView&quot;:4,&quot;spaceBetween&quot;:20},&quot;992&quot;:{&quot;slidesPerView&quot;:5,&quot;spaceBetween&quot;:20},&quot;1200&quot;:{&quot;slidesPerView&quot;:6,&quot;spaceBetween&quot;:16}}}">
                        <div class="swiper-wrapper d-flex" id="swiper-wrapper-8232bcab79c9c18a" aria-live="polite">
                            <div class="swiper-slide swiper-slide-active col-md-4" role="group" aria-label="1 / 7"
                                style="width: 261px; margin-right: 16px;">
                                <div class="position-relative text-decoration-none product-card h-100">
                                    <div class="d-flex flex-column justify-content-between h-100">
                                        <div>
                                            <!-- Thumbnail Image with Modal Trigger -->
                                            <div
                                                class="border border-1 border-translucent rounded-3 position-relative mb-3">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal"
                                                    onclick="showImage('{{ $item['image'] }}')">
                                                    <img height="380" width="300" src="{{ $item['image'] }}"
                                                        alt="Thumbnail Image">
                                                </a>

                                            </div>

                                            <!-- Modal Structure for Full Image View -->
                                            <div class="modal fade" id="imageModal" tabindex="-1"
                                                aria-labelledby="imageModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="imageModalLabel">Image Preview
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <!-- Full-Size Image Displayed in Modal -->
                                                            <img id="modalImage" src="" class="img-fluid rounded"
                                                                alt="Full-Size Image">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- <a> --}}
                                            <h6 class="mb-2 text-body-highlight">
                                                {{ $item['name'] }}
                                            </h6>
                                            {{-- </a> --}}

                                        </div>
                                        <div>
                                            <h3 class="text-body-emphasis">
                                                {{ $item['category_name'] }}
                                                <a href="{{ route('edit_category', $item['id']) }}"
                                                    class="btn btn-warning btn-sm content-icon">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="delete_product/{{ $item['id'] }}"
                                                    class="btn btn-danger btn-sm content-icon"
                                                    onclick="return confirm('Are you sure you want to delete this Product?');">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>

                                            </h3>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{-- @endforeach --}}
                        </div>
                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                    </div>

                </div>
            @endforeach


            {{-- </div> --}}
        </div>

        {{-- <div id="product_container">

        </div> --}}

        <!-- Pagination Links for Products -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <form action="{{ route('add-product') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="addDealModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="addDealModal" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content bg-body-highlight p-6">
                    <div class="modal-header justify-content-between border-0 p-0 mb-2">
                        <h3 class="mb-0">Add Product Detail</h3><button type="reset"
                            class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="modal" aria-label="Close"><span
                                class="fas fa-times text-danger"></span></button>
                    </div>
                    {{-- @dd($categories); --}}
                    <div class="modal-body px-0">
                        <div class="row g-4">
                            <div class="col-lg-6">

                                <div class="mb-4"><label class="text-body-highlight fw-bold mb-2">Select Product
                                        Category</label><select class="form-select" name='category_id'>
                                        <option>Select</option>
                                        @foreach ($categories as $item)
                                            <option name='category_id' value="{{ $item->id }}">{{ $item->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @error('category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4"><label class="text-body-highlight fw-bold mb-2">Product
                                        Name</label><input class="form-control" type="text" name='p_name'
                                        placeholder="Enter Product name" />
                                    @error('p_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <input type="hidden" name="stock_status" value="in_stock">
                                <div class="mb-4"><label class="text-body-highlight fw-bold mb-2">Image</label>
                                    <div class="input-group"><input class="form-control" type="file"
                                            placeholder="Enter image url" name='image' /><span
                                            class="input-group-text"><span
                                                class="fas fa-image text-body-tertiary"></span></span>
                                    </div>
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>

                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-6 px-0 pb-0"><button type="reset"
                            class="btn btn-link text-danger px-3 my-0" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button><button type="submit" class="btn btn-primary my-0">Create
                            Deal</button></div>
                </div>
    </form>
    </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-open the modal if there are validation errors
            @if ($errors->any())
                var myModal = new bootstrap.Modal(document.getElementById('addDealModal'), {});
                myModal.show();
            @endif

            // Clear form fields and error messages when the modal is closed
            var productModal = document.getElementById('addDealModal');
            productModal.addEventListener('hide.bs.modal', function() {
                // Clear all input fields
                productModal.querySelectorAll('input').forEach(input => input.value = '');
                productModal.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

                // Remove error messages
                productModal.querySelectorAll('.text-danger').forEach(error => error.textContent = '');
            });
        });
    </script>

    <!-- JavaScript to Change Modal Image Source -->
    <script>
        function showImage(src) {
            document.getElementById('modalImage').src = src;
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#searchInput').on('input', function() {
                let query = $(this).val();

                if (query.length > 2) { // Start searching when input is 3 characters or more
                    $.ajax({
                        url: "{{ route('search-products') }}", // Your route for searching
                        method: 'GET',
                        data: {
                            query: query
                        },
                        success: function(response) {
                            console.log('Response:', response);
                            let html = '';

                            if (response && response.length > 0) {
                                response.forEach(item => {
                                    let temp_imageUrl = item.image;

                                    // Construct HTML for each product item
                                    html += `
                            <div class="swiper-theme-container products-slider d-flex col-md-3">
                                <div class="swiper theme-slider swiper-initialized swiper-horizontal mb-3 swiper-backface-hidden"
                                    data-swiper="{&quot;slidesPerView&quot;:1,&quot;spaceBetween&quot;:16,&quot;breakpoints&quot;:{&quot;450&quot;:{&quot;slidesPerView&quot;:2,&quot;spaceBetween&quot;:16},&quot;576&quot;:{&quot;slidesPerView&quot;:3,&quot;spaceBetween&quot;:20},&quot;768&quot;:{&quot;slidesPerView&quot;:4,&quot;spaceBetween&quot;:20},&quot;992&quot;:{&quot;slidesPerView&quot;:5,&quot;spaceBetween&quot;:20},&quot;1200&quot;:{&quot;slidesPerView&quot;:6,&quot;spaceBetween&quot;:16}}}">
                                    <div class="swiper-wrapper d-flex" aria-live="polite">
                                        <div class="swiper-slide swiper-slide-active col-md-4" role="group" aria-label="1 / 7"
                                            style="width: 261px; margin-right: 16px;">
                                            <div class="position-relative text-decoration-none product-card h-100">
                                                <div class="d-flex flex-column justify-content-between h-100">
                                                    <div>
                                                        <!-- Thumbnail Image with Modal Trigger -->
                                                        <div class="border border-1 border-translucent rounded-3 position-relative mb-3">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal"
                                                                onclick="showImage('${temp_imageUrl}')">
                                                                <img height="380" width="300" src="${temp_imageUrl}"
                                                                    alt="Thumbnail Image">
                                                            </a>
                                                        </div>

                                                        <!-- Modal Structure for Full Image View -->
                                                        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        <img id="modalImage" src="" class="img-fluid rounded" alt="Full-Size Image">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <h6 class="mb-2 text-body-highlight">${item.p_name}</h6>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-body-emphasis">${item.category_name}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                                });

                                $('#main_data').html(html); // Append the generated HTML
                            } else {
                                $('#main_data').html('<p>No products found</p>');
                            }
                        },
                        error: function(error) {
                            console.log('Error fetching products:', error);
                        }
                    });
                } else {
                    // If the search input is empty, fetch all products
                    $.ajax({
                        url: "{{ route('search-products') }}", // Your route for fetching all products
                        method: 'GET',
                        data: {}, // No query parameter
                        success: function(response) {
                            console.log('Response for all products:', response);
                            let html = '';

                            if (response && response.length > 0) {
                                response.forEach(item => {
                                    let temp_imageUrl = item.image;

                                    // Construct HTML for each product item
                                    html += `
                            <div class="swiper-theme-container products-slider d-flex col-md-3">
                                <div class="swiper theme-slider swiper-initialized swiper-horizontal mb-3 swiper-backface-hidden"
                                    data-swiper="{&quot;slidesPerView&quot;:1,&quot;spaceBetween&quot;:16,&quot;breakpoints&quot;:{&quot;450&quot;:{&quot;slidesPerView&quot;:2,&quot;spaceBetween&quot;:16},&quot;576&quot;:{&quot;slidesPerView&quot;:3,&quot;spaceBetween&quot;:20},&quot;768&quot;:{&quot;slidesPerView&quot;:4,&quot;spaceBetween&quot;:20},&quot;992&quot;:{&quot;slidesPerView&quot;:5,&quot;spaceBetween&quot;:20},&quot;1200&quot;:{&quot;slidesPerView&quot;:6,&quot;spaceBetween&quot;:16}}}">
                                    <div class="swiper-wrapper d-flex" aria-live="polite">
                                        <div class="swiper-slide swiper-slide-active col-md-4" role="group" aria-label="1 / 7"
                                            style="width: 261px; margin-right: 16px;">
                                            <div class="position-relative text-decoration-none product-card h-100">
                                                <div class="d-flex flex-column justify-content-between h-100">
                                                    <div>
                                                        <div class="border border-1 border-translucent rounded-3 position-relative mb-3">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal"
                                                                onclick="showImage('${temp_imageUrl}')">
                                                                <img height="380" width="300" src="${temp_imageUrl}"
                                                                    alt="Thumbnail Image">
                                                            </a>
                                                        </div>

                                                        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        <img id="modalImage" src="" class="img-fluid rounded" alt="Full-Size Image">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <h6 class="mb-2 text-body-highlight">${item.p_name}</h6>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-body-emphasis">${item.category_name}
                                                         <a href="#" class="text-danger ms-2 float-end ml-5" title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                            <a href="#" class="text-primary ms-2 float-end ml-5" title="Edit">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                                });

                                $('#main_data').html(
                                    html); // Append the generated HTML for all products
                            } else {
                                $('#main_data').html('<p>No products found</p>');
                            }
                        },
                        error: function(error) {
                            console.log('Error fetching all products:', error);
                        }
                    });
                }
            });
        });


        // Function to handle image modal
        function showImage(imageUrl) {
            document.getElementById('modalImage').src = imageUrl;
        }
    </script>

    <script>
        function editCategory(id) {
            // Redirect to edit page
            window.location.href = `/categories/${id}/edit`;
        }

        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                $.ajax({
                    url: `/product/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        id: id
                    },
                    success: function(response) {
                        console.log('Response:', response);
                        alert('Product deleted successfully');
                        location.reload(); // Reloads the page to reflect the deletion
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr.responseText);
                        alert('Failed to delete product');
                    }
                });
            }
        }
    </script>
@endsection
