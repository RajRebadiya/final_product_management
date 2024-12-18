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

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        }

        .card-header {
            border-radius: 15px 15px 0 0;
        }

        .form-check-label {
            font-size: 0.9rem;
        }
    </style>
    <div class="container mt-5">
        <!-- Page Heading -->
        <div class="text-center mb-4">
            <h1 class="display-5 fw-bold">Create New Role</h1>
            <p class="text-muted">Fill in the details below to define a new role and assign permissions.</p>
        </div>

        <!-- Form Card -->
        <div class="card shadow-lg border-0">
            <div class="card-body p-5">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf

                    <!-- Role Name Input -->
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">Role Name</label>
                        <input type="text" class="form-control form-control-lg" id="name" name="name"
                            placeholder="Enter role name" required>
                    </div>

                    <!-- Permissions Section -->
                    <h4 class="fw-bold mb-3">Assign Permissions</h4>
                    <div class="row">
                        @foreach ($permissions as $module => $actions)
                            <div class="col-md-6 mb-4">
                                <div class="card shadow border-0 h-100">
                                    <div
                                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"
                                            style="
                                        color: white;
                                    ">
                                            {{ ucfirst($module) }}</h5>
                                        <!-- Select All Checkbox -->
                                        <div>
                                            <input type="checkbox" id="select-all-{{ $module }}"
                                                class="form-check-input"
                                                onclick="toggleModule('{{ $module }}', this)">
                                            <label for="select-all-{{ $module }}" class="form-check-label text-white">
                                                Select All
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @foreach (['read', 'create', 'update', 'delete'] as $action)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input module-checkbox-{{ $module }}"
                                                    type="checkbox"
                                                    name="permissions[{{ $module }}][{{ $action }}]"
                                                    value="1">
                                                <label class="form-check-label">
                                                    {{ ucfirst($action) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5">Create Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModule(module, checkbox) {
            const moduleCheckboxes = document.querySelectorAll(`.module-checkbox-${module}`);
            moduleCheckboxes.forEach(cb => cb.checked = checkbox.checked);
        }
    </script>
@endsection
