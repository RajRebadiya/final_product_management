@extends('admin.layout.template')


@section('content')
    <style>
        /* General Styling */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .page-wrapper {
            padding: 20px;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
        }

        .page-header h1 {
            font-size: 30px;
            font-weight: 700;
            color: #343a40;
        }

        .btn-add-staff {
            background-color: #0d6efd;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 14px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-add-staff i {
            margin-right: 6px;
        }

        .btn-add-staff:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
        }

        /* Table Container */
        .table-container {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #ffffff;
        }

        .staff-table {
            width: 100%;
            border-collapse: collapse;
        }

        .staff-table th {
            background-color: #6c757d;
            color: white;
            padding: 12px;
            text-transform: uppercase;
            font-size: 13px;
            text-align: center;
        }

        .staff-table td {
            padding: 12px;
            text-align: center;
            font-size: 14px;
            border-bottom: 1px solid #dee2e6;
        }

        .staff-table tr:hover {
            background-color: #f1f3f5;
        }

        /* Button Styling */
        .action-btn {
            padding: 6px 10px;
            /* font-size: 12px; */
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
        }

        .btn-edit {
            background-color: #ffc107;
            color: white;
            margin-right: 8px;
            padding: 17px 40px;
            color: #212529;
        }

        .btn-edit:hover {
            background-color: #e0a800;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            padding: 17px 40px;
            color: #212529;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 15px;
            color: #adb5bd;
        }

        .empty-state i {
            font-size: 48px;
            color: #ced4da;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 16px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .btn-add-staff {
                font-size: 12px;
                padding: 8px 12px;
            }

            .staff-table th,
            .staff-table td {
                font-size: 12px;
                padding: 10px;
            }

            .page-header h1 {
                font-size: 24px;
            }
        }
    </style>

    <div class="page-wrapper">
        <div class="page-header">
            <h1>Staff Management Dashboard</h1>
            <a href="{{ route('staff.create') }}" class="btn-add-staff">
                <i class="fas fa-user-plus"></i> Add Staff
            </a>
        </div>

        @if ($staffs->isEmpty())
            <div class="empty-state">
                <i class="fas fa-user-friends"></i>
                <p>No staff members available. Add new staff to begin.</p>
            </div>
        @else
            @php
                $user = Auth::guard('staff')->user();
                $role = \App\Models\Role::where('id', $user->role_id)->first();
                $permissions = $role->permissions;
            @endphp

            <!-- Dashboard -->

            <div class="table-container">
                <table class="staff-table table table-hover table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Market Name</th>

                            <th>Role</th>
                            <th>Email</th>
                            <th>Password</th>
                            @if (!empty($permissions['User']['update']) && $permissions['User']['update'])
                                <th>Edit</th>
                            @endif
                            @if (!empty($permissions['User']['delete']) && $permissions['User']['delete'])
                                <th>Delete</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staffs as $staff)
                            <tr>
                                <td>{{ $staff->name }}</td>
                                <td>{{ $staff->market_name }}</td>
                                <td>{{ $staff->role->name ?? 'N/A' }}</td>
                                <td>{{ $staff->email ?? 'N/A' }}</td>
                                <td>{{ $staff->password ?? 'N/A' }}</td>
                                @if (!empty($permissions['User']['update']) && $permissions['User']['update'])
                                    <td>
                                        <a href="{{ route('staff.edit', $staff->id) }}"
                                            class="action-btn btn-edit btn btn-warning btn-sm content-icon">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </td>
                                @endif
                                @if (!empty($permissions['User']['delete']) && $permissions['User']['delete'])
                                    <td>
                                        <button class="action-btn btn-delete delete-btn btn btn-danger btn-sm content-icon"
                                            data-id="{{ $staff->id }}" data-name="{{ $staff->name }}">
                                            <i class="fa-solid fa-trash "></i>
                                        </button>
                                        <form id="delete-form-{{ $staff->id }}"
                                            action="{{ route('staff.destroy', $staff->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const staffId = this.dataset.id;
                const staffName = this.dataset.name;

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete ${staffName}. This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${staffId}`).submit();
                    }
                });
            });
        });
    </script>
@endsection
