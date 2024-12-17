@extends('admin.layout.template')
@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
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
            <h3>Category List</h3>
            {{-- <div class="d-flex flex-between-center mb-3 search-box navbar-top-search-box d-none d-lg-block"
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
            </div> --}}

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
                </svg><!-- <span class="fas fa-plus me-2"></span> Font Awesome fontawesome.com -->Add New Category</button>
        </div>

        <form method="GET" action="{{ route('category') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search by Design No or Category" aria-label="Search" />
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>
        {{-- @dd($categories); --}}
        <div id="tableExample3"
            data-list='{"valueNames":["no","category_name","name","image" ,"stock_status" , "status"],"page":10,"pagination":true}'>
            <div class="search-box mb-3 mx-auto">

            </div>
            <div class="table-responsive">
                <table class="table table-striped table-sm fs-9 mb-0">
                    <thead>
                        <tr>
                            <th class="sort border-top border-translucent ps-3" data-sort="no">No</th>
                            {{-- <th class="sort border-top" data-sort="image">image</th> --}}
                            <th class="sort border-top border-translucent ps-3" data-sort="category_name">Category Name</th>
                            <th class="sort border-top" data-sort="price">Price</th>
                            {{-- <th class="sort border-top border-translucent ps-3" data-sort="stock_status">Stock Status</th> --}}
                            <th class="sort border-top text-middle align-middle border-translucent ps-3" data-sort="status">
                                Status</th>
                            <th class="sort text-center align-middle pe-0 border-top " scope="col">Edit</th>
                            <th class="sort text-middle align-middle pe-0 border-top" scope="col">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach ($categories as $item)
                            <tr>
                                <td class="align-middle ps-3 no">{{ $loop->iteration }}</td>

                                <td class="align-middle ps-3 category_name">{{ $item['name'] }}</td>
                                <td class="align-middle ps-3 price">{{ $item['price'] }}</td>
                                <!-- Status Dropdown with Colors -->
                                <td class="align-middle ps-3 status">
                                    <form class="align-middle" action="{{ route('category_update_status') }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="category_id" value="{{ $item['id'] }}">
                                        <select name="status" class="form-select form-select-sm"
                                            style="background-color: {{ $item['status'] == 'Active' ? '#d4edda' : '#f8d7da' }}; color: {{ $item['status'] == 'Active' ? '#155724' : '#721c24' }}; width: 50%;"
                                            onchange="this.form.submit()">
                                            <option value="Active" {{ $item['status'] == 'Active' ? 'selected' : '' }}>
                                                ACTIVE</option>
                                            <option value="Inactive" {{ $item['status'] == 'Inactive' ? 'selected' : '' }}>
                                                INACTIVE</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('edit_category') }}" method="GET" style="display:inline;">
                                        <input type="hidden" name="category_id" value="{{ $item['id'] }}">
                                        <button type="submit" class="btn btn-warning btn-sm content-icon">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="align-middle text-middle pe-0">
                                    <button class="btn btn-danger btn-sm content-icon"
                                        onclick="confirmDeletion('{{ $item['id'] }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between mt-3"><span class="d-none d-sm-inline-block"
                    data-list-info="data-list-info"></span>
                <div class="d-flex"><button class="page-link" data-list-pagination="prev"><span
                            class="fas fa-chevron-left"></span></button>
                    <ul class="mb-0 pagination"></ul><button class="page-link pe-0" data-list-pagination="next"><span
                            class="fas fa-chevron-right"></span></button>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('add-category-post') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="addDealModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="addDealModal" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content bg-body-highlight p-6">
                    <div class="modal-header justify-content-between border-0 p-0 mb-2">
                        <h3 class="mb-0">Add Category Detail</h3><button type="reset"
                            class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="modal" aria-label="Close"><span
                                class="fas fa-times text-danger"></span></button>
                    </div>
                    {{-- @dd($categories); --}}
                    <div class="modal-body px-0">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="mb-4"><label class="text-body-highlight fw-bold mb-2">Category
                                        Name</label><input class="form-control" type="text" name='name'
                                        placeholder="Enter Category name" />
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>
                            <div class="col-lg-6">
                                <div class="mb-4"><label class="text-body-highlight fw-bold mb-2">Category
                                        Price</label><input class="form-control" type="text" name='price'
                                        placeholder="Enter Category Price" />
                                    @error('price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>

                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-6 px-0 pb-0" style="justify-content: normal;"><button
                            type="reset" class="btn btn-link text-danger float-start px-3 my-0" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button><button type="submit"
                            class="btn btn-primary float-start my-0">Create
                            Category</button></div>
                </div>
    </form>
    </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        function confirmDeletion(itemId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, redirect to delete route
                    window.location.href = `delete_category/${itemId}`;
                }
            });
        }
    </script>

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
        function editCategory(id) {
            // Redirect to edit page
            window.location.href = `/categories/${id}/edit`;
        }
    </script>
@endsection
