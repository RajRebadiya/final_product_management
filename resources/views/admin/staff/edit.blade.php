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
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">Edit Staff Member</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('staff.update', $staff->id) }}" method="POST" class="needs-validation"
                            novalidate>
                            @csrf
                            @method('PUT')

                            <!-- Name Field -->
                            <div class="form-group mb-4">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name"
                                    value="{{ old('name', $staff->name) }}" required>
                                <div class="invalid-feedback">Please provide a valid name.</div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="market_name" class="form-label">Market Name <span
                                        class="text-danger">*</span></label>
                                <select name="market_name" class="form-control form-select"
                                    value="{{ old('market_name') ?? $staff->market_name }}" id="market_name" required>
                                    <option value="" disabled selected>Select Market Name</option>
                                    <option value="GTM">GTM</option>
                                    <option value="MTM">MTM</option>
                                    <option value="ATM">ATM</option>
                                </select>
                                <div class="invalid-feedback">Please select a market name.</div>
                                @error('market_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Mobile No Field -->
                            <div class="form-group mb-4">
                                <label for="mobile_no" class="form-label">Mobile No <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="mobile_no" value="{{ old('mobile_no', $staff->mobile_no) }}"
                                    class="form-control" id="mobile_no" placeholder="Enter Mobile No" required>
                                <div class="invalid-feedback">Please provide a valid mobile number.</div>
                                @error('mobile_no')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="form-group mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    value="{{ old('email', $staff->email) }}" required>
                            </div>

                            <!-- Role Field -->
                            <div class="form-group mb-4">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-control form-select" id="role" required>
                                    <option value="" disabled>Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ $staff->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select a role.</div>
                            </div>

                            <!-- Password Field (Optional) -->
                            <div class="form-group mb-4">
                                <label for="password" class="form-label">Password (Leave blank to keep current
                                    password)</label>
                                <input type="password" name="password" class="form-control" id="password">
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Update Staff</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert for custom validation messages -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function() {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');

            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
@endsection
