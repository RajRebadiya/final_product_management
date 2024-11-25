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
    <div class="content" style='padding: 10px;'>
        <h2 class="mb-4">Create a Category</h2>
        <div class="row">
            <div class="col-xl-6">
                <form class="row g-3 mb-6" action="{{ route('add-category-post') }}" method="post">
                    @csrf
                    <div class="col-sm-6 col-md-8">
                        <div class="form-floating"><input class="form-control" name='name' value='{{ old('name') }}'
                                id="floatingInputGrid" type="text" placeholder="Project title"><label
                                for="floatingInputGrid">Category Name</label>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 gy-6">
                        <div class="row g-3 justify-content-start">
                            <div class="col-auto"><button type='reset'
                                    class="btn btn-phoenix-primary px-5">Cancel</button></div>
                            <div class="col-auto"><button type='submit' class="btn btn-primary px-5 px-sm-15">Add
                                    Category</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
