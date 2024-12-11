@extends('admin.layout.template')

@section('content')
    <!-- Include SweetAlert2 CDN in your layout file (usually in the head or before closing </body>) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>

    </style>
    <div class="container mt-4">
        <div class="container">

            <h1 class="mb-4 text-center d-inline-block">Manage Roles</h1>

            <a href="{{ route('roles.create') }}" class="btn btn-primary mb-4 float-end">Create New Role</a>
        </div>

        <table class="table table-bordered  align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Role Name</th>
                    <th scope="col">Permissions</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr class="align-middle border-right">
                        <td class="align-middle">
                            <p class="fw-bold"
                                style="
                            margin-left: 38px;
                        ">
                                {{ $role->name }}</p>
                        </td>
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

                        <td class='d-flex justify-content-space-between'>
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary">Edit</a>

                            <!-- Delete button that triggers SweetAlert -->
                            <button type="button" class="btn btn-danger"
                                style="
                            margin: 0px 11px;
                        "
                                onclick="deleteRole({{ $role->id }})">Delete</button>

                            <!-- The form will be hidden and submitted using JavaScript after SweetAlert confirmation -->
                            <form id="delete-form-{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}"
                                method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>


                    </tr>
                @endforeach
            </tbody>
        </table>
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
