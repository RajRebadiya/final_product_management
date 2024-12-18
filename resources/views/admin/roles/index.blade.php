@extends('admin.layout.template')

@section('content')
    @if (session('success'))
        <div class="alert alert-secondary alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!-- Include SweetAlert2 CDN in your layout file (usually in the head or before closing </body>) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Add shadow effect to the table container */
        .table-responsive {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 30px;
        }

        /* Table border styling */
        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }

        .table th {
            background-color: #343a40;
            color: white;
        }

        /* .table tr td {
                                                                                                                                                                        padding: 13px 31px;
                                                                                                                                                                    } */

        /* Styling for badges */
        .badge {
            padding: 5px 10px;
            font-size: 0.875rem;
            border-radius: 5px;
        }

        .table>tbody>tr>td:last-child {
            padding-right: 0;
        }

        .badge.bg-success {
            background-color: #28a745;
        }

        .badge.bg-danger {
            background-color: #dc3545;
        }

        /* Styling for action buttons */
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.875rem;
        }

        /* Hover effect for delete button */
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
    </style>

    <div class="container mt-4">
        <div class="container">
            <h1 class="mb-4 text-center d-inline-block">Manage Roles</h1>
            <a href="{{ route('roles.create') }}" class="btn btn-primary mb-4 float-end">Create New Role</a>
        </div>

        @php
            $user = Auth::guard('staff')->user();
            $role = \App\Models\Role::where('id', $user->role_id)->first();
            $permissions = $role->permissions;
        @endphp

        <div class="table-responsive shadow-lg rounded-lg border border-muted">
            <table class="table  table-bordered ">
                <thead>
                    <tr>
                        <th scope="col">Role Name</th>
                        <th scope="col">Permissions</th>

                        <th scope="col">Actions</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr class="align-middle">
                            <td class="align-middle">
                                <p class="fw-bold" style="margin-left: 38px;">{{ $role->name }}</p>
                            </td>
                            {{-- @if (!empty($permissions['Permissions']['delete']) && $permissions['Permissions']['delete']) --}}
                            <td>
                                <div class="row">
                                    @foreach ($role->permissions as $module => $actions)
                                        <div class="col-12 mb-3">
                                            <h5 class="text-primary">{{ ucfirst($module) }}</h5>
                                            <div class="d-flex flex-wrap">
                                                @foreach ($actions as $action => $allowed)
                                                    <span class="badge {{ $allowed ? 'bg-success' : 'bg-danger' }} me-2">
                                                        {{ ucfirst($action) }}: {{ $allowed ? 'Yes' : 'No' }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>

                            <td class='d-flex justify-content-between' style="padding: 14px">
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm"
                                    style="
                                    padding: 8px 40px;
                                ">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Delete button that triggers SweetAlert -->
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="deleteRole({{ $role->id }})"
                                    style="
                                    padding: 8px 40px;
                                    margin-left: 10px;
                                ">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                <!-- The form will be hidden and submitted using JavaScript after SweetAlert confirmation -->
                                <form id="delete-form-{{ $role->id }}"
                                    action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteRole(roleId) {
            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, submit the delete form
                    document.getElementById('delete-form-' + roleId).submit();
                }
            });
        }
    </script>
@endsection
