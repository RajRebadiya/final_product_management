@extends('admin.layout.template')

@section('content')
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            background: #ffffff;
            border-radius: 15px;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            transition: all 0.3s;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .btn-outline-secondary {
            transition: all 0.3s;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        }
    </style>
    <div class="container mt-5">
        <!-- Page Heading -->
        <div class="text-center mb-4">
            <h1 class="display-5 fw-bold">Add New Permission</h1>
            <p class="text-muted">Fill in the details below to create a new permission module.</p>
        </div>

        <!-- Form Card -->
        <div class="card shadow-lg border-0">
            <div class="card-body p-5">
                <form action="{{ route('permissions_add') }}" method="POST">
                    @csrf

                    <!-- Module Name Input -->
                    <div class="mb-4">
                        <label for="module" class="form-label fw-bold">Module Name</label>
                        <input type="text" class="form-control form-control-lg" id="module" name="module"
                            placeholder="Enter module name" required>
                        <small class="form-text text-muted">Example: User Management, Roles, Settings, etc.</small>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5">Create Permission</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="{{ route('permission_list') }}" class="btn btn-outline-secondary">Back to Permissions</a>
        </div>
    </div>
@endsection
