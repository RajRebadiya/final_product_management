@extends('admin.layout.template')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center">Add New Permission</h1>

        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                {{-- <label for="role_id" class="form-label">Role</label> --}}
                <input type="hidden" class="" name="role_id" value='{{ $roles->id }}'>
            </div>

            <div class="mb-3">
                <label for="module" class="form-label">Module Name</label>
                <input type="text" class="form-control" id="module" name="module" required>
            </div>

            <div class="mb-3">
                <label for="permissions" class="form-label">Permissions</label>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="read" name="permissions[read]" value="true">
                    <label class="form-check-label" for="read">Read</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="create" name="permissions[create]"
                        value="true">
                    <label class="form-check-label" for="create">Create</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="update" name="permissions[update]"
                        value="true">
                    <label class="form-check-label" for="update">Update</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="delete" name="permissions[delete]"
                        value="true">
                    <label class="form-check-label" for="delete">Delete</label>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success btn-lg mt-4">Create Permission</button>
            </div>
        </form>
    </div>
@endsection
