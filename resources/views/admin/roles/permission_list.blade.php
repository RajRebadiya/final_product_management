@extends('admin.layout.template')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @php
        $user = Auth::guard('staff')->user();
        $role = \App\Models\Role::where('id', $user->role_id)->first();
        $permissions = $role->permissions;
    @endphp

    <div class="container mt-5">
        <h1 class="text-center mb-4" style="font-size: 2.5rem; font-weight: bold; color: #333;">Permission Management</h1>

        <div class="table-responsive shadow-lg rounded-lg border border-muted">
            <table class="table table-striped table-bordered table-hover">
                <thead style="background-color: #343a40; color: white;">
                    <tr>
                        <th scope="col" style='color: white;'>ID</th>
                        <th scope="col" style='color: white;'>Module Name</th>
                        <th scope="col" style='color: white;'>Created At</th>
                        <th scope="col" style='color: white;'>Updated At</th>
                        @if (!empty($permissions['Permissions']['delete']) && $permissions['Permissions']['delete'])
                            <th scope="col" style='color: white;'>Delete</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissionsss as $permission)
                        <tr class="align-middle">
                            <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                            <td>{{ $permission->module }}</td>
                            <td>{{ $permission->created_at->format('d M Y, h:i A') }}</td>
                            <td>{{ $permission->updated_at->format('d M Y, h:i A') }}</td>
                            @if (!empty($permissions['Permissions']['delete']) && $permissions['Permissions']['delete'])
                                <td class="text-center">
                                    <button class="btn btn-danger btn-sm delete-permission"
                                        data-module="{{ $permission->module }}">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- SweetAlert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        document.querySelectorAll('.delete-permission').forEach(button => {
            button.addEventListener('click', function() {
                const module = this.dataset.module;

                Swal.fire({
                    title: `Are you sure?`,
                    text: `You are about to delete the permissions for the ${module} module. This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Call the delete function here (e.g., via AJAX or form submission)
                        deletePermission(module);
                    }
                });
            });
        });

        function deletePermission(module) {
            $.ajax({
                url: `/permissions/delete/${module}`,
                method: 'GET',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    Swal.fire('Deleted!', `The permissions for ${module} have been deleted.`, 'success');
                    // Optionally, remove the deleted row
                    $(`[data-module="${module}"]`).closest('tr').remove();
                },
                error: function() {
                    Swal.fire('Error!', 'There was an issue deleting the permissions. Please try again.',
                        'error');
                }
            });
        }
    </script>
@endsection
