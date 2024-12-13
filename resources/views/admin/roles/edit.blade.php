@extends('admin.layout.template')

@section('content')
    <style>
        .form-check-label {
            font-size: 0.9rem;
        }
    </style>
    <div class="container mt-4">
        <h1 class="mb-4 text-center">Edit Permissions for Role: <strong>{{ $role->name }}</strong></h1>

        <a href="{{ route('permissions.create', $role->id) }}" class="btn btn-primary mb-4">Add New Permission</a>

        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                @php
                    // Decode the role permissions to check which ones are assigned
                    $rolePermissions = $role->permissions;
                @endphp

                @foreach ($permissions as $module => $permission)
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ ucfirst($module) }}</h5>

                                <div>
                                    <input type="checkbox" id="select-all-{{ $module }}" class="form-check-input"
                                        onclick="toggleModule('{{ $module }}', this)">
                                    <label for="select-all-{{ $module }}" class="form-check-label text-white">
                                        Select All
                                    </label>
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach (['read', 'create', 'update', 'delete'] as $action)
                                    <div class="form-check">
                                        <input class="form-check-input module-checkbox-{{ $module }}" type="checkbox"
                                            name="permissions[{{ $module }}][{{ $action }}]" value="true"
                                            {{-- Check if this permission is already assigned to the role --}}
                                            {{ isset($rolePermissions[$module][$action]) && $rolePermissions[$module][$action] ? 'checked' : '' }}>
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

            <div class="text-center">
                <button type="submit" class="btn btn-success btn-lg mt-4 mb-5">Update Permissions</button>
            </div>
        </form>
    </div>
    <script>
        function toggleModule(module, checkbox) {
            const moduleCheckboxes = document.querySelectorAll(`.module-checkbox-${module}`);
            moduleCheckboxes.forEach(cb => cb.checked = checkbox.checked);
        }
    </script>
@endsection
