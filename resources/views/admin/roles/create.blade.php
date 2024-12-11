@extends('admin.layout.template')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center">Create New Role</h1>

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Role Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <h4>Assign Permissions</h4>
            <div class="row">
                @foreach ($permissions as $module => $actions)
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">{{ ucfirst($module) }}</h5>
                            </div>
                            <div class="card-body">
                                @foreach (['read', 'create', 'update', 'delete'] as $action)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="permissions[{{ $module }}][{{ $action }}]" value="1">
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
                <button type="submit" class="btn btn-success btn-lg mt-4">Create Role</button>
            </div>
        </form>
    </div>
@endsection
