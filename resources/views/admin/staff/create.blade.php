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
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h3 class="text-center mb-0">Add Staff Member</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('staff.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <div class="form-group mb-4">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="Enter full name" value="{{ old('name') }}" required>
                                <div class="invalid-feedback">Name is required.</div>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <label for="mobile_no" class="form-label">Mobile No <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="mobile_no" value="{{ old('mobile_no') }}" class="form-control"
                                    id="mobile_no" placeholder="Enter Mobile No" maxlength="10" required>
                                <div class="invalid-feedback">Valid mobile number required.</div>
                                @error('mobile_no')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                    id="email" placeholder="Enter email address" required>

                            </div>

                            <div class="form-group mb-4">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" id="password"
                                    placeholder="Enter a strong password" required>
                                <div class="invalid-feedback">Password is required.</div>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="c_password" class="form-label">Confirm Password <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="c_password" class="form-control" id="c_password"
                                    placeholder="Enter a confirm password" required>
                                <div class="invalid-feedback">Confirm Password is required.</div>
                                @error('c_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-control form-select" value="{{ old('role') }}"
                                    id="role" required>
                                    <option value="" disabled selected>Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select a role.</div>
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Add Staff</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')

            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
@endsection
