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
    <script>
        @if (session('login_success'))
            toastr.success('{{ session('login_success') }}', 'Welcome Back!', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000
            });
        @endif
    </script>
    {{-- <script>
        @if (session('error'))
            toastr.error('{{ session('error') }}', 'Error!', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000
            });
        @endif
    </script> --}}
    <div class="content">
        <div class="mb-9">
            <div class="row g-6">
                <div class="col-12 col-xl-12">
                    <div class="card mb-5">
                        <div class="card-header hover-actions-trigger position-relative mb-6" style="min-height: 130px; ">
                            <div class="bg-holder rounded-top"
                                style="background-image: linear-gradient(0deg, #000000 -3%, rgba(0, 0, 0, 0) 83%), url(../../assets/img/generic/59.png)">
                                <label class="cover-image-file-input" for="upload-settings-cover-image"></label>
                                <div class="hover-actions end-0 bottom-0 pe-1 pb-2 text-white dark__text-gray-1100"><span
                                        class="fa-solid fa-camera me-2"></span></div>
                            </div><label class="avatar avatar-4xl status-online feed-avatar-profile cursor-pointer"
                                for="upload-settings-porfile-picture"><img
                                    class="rounded-circle img-thumbnail shadow-sm border-0"
                                    src="../../assets/img/team/30.webp" width="200" alt="" /></label>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex flex-wrap mb-2 align-items-center">
                                        <h3 class="me-2">{{ Auth::guard('staff')->user()->name }}</h3><span
                                            class="fw-normal fs-8">{{ Auth::guard('staff')->user()->emp_code }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="col-12 col-xl-8">
                    <div class="border-bottom mb-4">
                        <div class="mb-6">
                            <form action="{{ route('update_profile') }}" method="post">
                                @csrf
                                <h4 class="mb-4">Personal Information</h4>
                                <div class="row g-3">
                                    <div class="col-12 col-md-4 col-sm-6">
                                        <div class="form-icon-container">
                                            <div class="form-floating"><input class="form-control form-icon-input"
                                                    id="firstName" type="text"
                                                    value="{{ Auth::guard('staff')->user()->name }}" name='name'
                                                    placeholder="First Name" /><label
                                                    class="text-body-tertiary form-icon-label" for="firstName">Your
                                                    NAME</label></div><span
                                                class="fa-solid fa-user text-body fs-9 form-icon"></span>
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-sm-4">
                                        <div class="form-icon-container">
                                            <div class="form-floating"><input class="form-control form-icon-input"
                                                    id="emailSocial" type="email" name='email'
                                                    value="{{ Auth::guard('staff')->user()->email }}"
                                                    placeholder="Email" /><label class="text-body-tertiary form-icon-label"
                                                    for="emailSocial">ENTER YOUR
                                                    EMAIL</label></div><span
                                                class="fa-solid fa-envelope text-body fs-9 form-icon"></span>
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-sm-4">
                                        <div class="form-icon-container">
                                            <div class="form-floating"><input class="form-control form-icon-input"
                                                    id="phone" type="tel"
                                                    value="{{ Auth::guard('staff')->user()->mobile_no }}" name='mobile_no'
                                                    placeholder="Phone" /><label class="text-body-tertiary form-icon-label"
                                                    for="phone">ENTER YOUR
                                                    PHONE</label></div><span
                                                class="fa-solid fa-phone text-body fs-9 form-icon"></span>
                                            @error('mobile_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                </div>
                                <div class="row g3">
                                    <h4 class="mb-4 mt-6">Change Password</h4>
                                    <div class="col-md-4  col-sm-4">
                                        <div class="form-icon-container mb-3">
                                            <div class="form-floating"><input class="form-control form-icon-input"
                                                    id="oldPassword" type="password" name='old_password'
                                                    placeholder="Old password" /><label
                                                    class="text-body-tertiary form-icon-label" for="oldPassword">Old
                                                    Password</label></div><span
                                                class="fa-solid fa-lock text-body fs-9 form-icon"></span>
                                            @error('old_password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4  col-sm-4">
                                        <div class="form-icon-container mb-3">
                                            <div class="form-floating"><input class="form-control form-icon-input"
                                                    id="newPassword" type="password" name='password'
                                                    placeholder="New password" /><label
                                                    class="text-body-tertiary form-icon-label" for="newPassword">New
                                                    Password</label></div><span
                                                class="fa-solid fa-key text-body fs-9 form-icon"></span>
                                            @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4  col-sm-4">
                                        <div class="form-icon-container">
                                            <div class="form-floating"><input class="form-control form-icon-input"
                                                    id="newPassword2" type="password" placeholder="Confirm New password"
                                                    name='confirm_password' /><label
                                                    class="text-body-tertiary form-icon-label" for="newPassword2">Confirm
                                                    New
                                                    Password</label></div><span
                                                class="fa-solid fa-key text-body fs-9 form-icon"></span>
                                            @error('confirm_password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div class="text-end mb-6">
                                    <div><button class="btn btn-phoenix-secondary me-2">Cancel Changes</button><button
                                            class="btn btn-phoenix-primary" type="submit">Save Information</button></div>
                                </div>
                            </form>

                        </div>
                        <div class="row gy-5">
                            <div class="col-12 col-md-6">
                                <h4 class="text-body-emphasis">Account Deletion</h4>
                                <button class="btn btn-phoenix-danger mt-3">Delete account</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
