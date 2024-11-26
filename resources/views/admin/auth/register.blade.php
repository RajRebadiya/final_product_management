<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">


<!-- Mirrored from prium.github.io/phoenix/v1.18.0/pages/authentication/card/sign-up.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 12 Nov 2024 09:35:55 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>Veer Creation | Register</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/veer_logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('assets/img/favicons/veer_creation-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('assets/img/favicons/veer_creation-16x16.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicons/veer_creation-32x32.ico') }}">
    <link rel="manifest" href="{{ asset('assets/img/favicons/manifest.json') }}">
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/favicons/mstile-150x150.png') }}">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('assets/vendors/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap"
        rel="stylesheet">
    <link href="{{ asset('assets/vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/unicons.iconscout.com/release/v4.0.8/css/line.css') }}">
    <link href="{{ asset('assets/css/theme-rtl.min.css') }}" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="{{ asset('assets/css/theme.min.css') }}" type="text/css" rel="stylesheet" id="style-default">
    <link href="{{ asset('assets/css/user-rtl.min.css') }}" type="text/css" rel="stylesheet" id="user-style-rtl">
    <link href="{{ asset('assets/css/user.min.css') }}" type="text/css" rel="stylesheet" id="user-style-default">
    <script>
        var phoenixIsRTL = window.config.config.phoenixIsRTL;
        if (phoenixIsRTL) {
            var linkDefault = document.getElementById('style-default');
            var userLinkDefault = document.getElementById('user-style-default');
            linkDefault.setAttribute('disabled', true);
            userLinkDefault.setAttribute('disabled', true);
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            var linkRTL = document.getElementById('style-rtl');
            var userLinkRTL = document.getElementById('user-style-rtl');
            linkRTL.setAttribute('disabled', true);
            userLinkRTL.setAttribute('disabled', true);
        }
    </script>
</head>

<body>
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
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <div class="container-fluid bg-body-tertiary dark__bg-gray-1200">
            <div class="bg-holder bg-auth-card-overlay" style="background-image:url(../../../assets/img/bg/37.png);">
            </div>
            <!--/.bg-holder-->
            <div class="row flex-center position-relative min-vh-100 g-0 py-5">
                <div class="col-11 col-sm-10 col-xl-8">
                    <div class="card border border-translucent auth-card">
                        <div class="card-body pe-md-0">
                            <div class="row align-items-center gx-0 gy-7">
                                <div
                                    class="col-auto bg-body-highlight dark__bg-gray-1100 rounded-3 position-relative overflow-hidden auth-title-box">
                                    <div class="bg-holder" style="background-image:url(../../../assets/img/bg/38.png);">
                                    </div>
                                    <!--/.bg-holder-->
                                    <div
                                        class="position-relative px-4 px-lg-7 pt-7 pb-7 pb-sm-5 text-center text-md-start pb-lg-7 card-sign-up">
                                        <h3 class="mb-3 text-body-emphasis fs-7">User Authentication</h3>
                                        <p class="text-body-tertiary">Give yourself some hassle-free development process
                                            with the uniqueness of veer creation!</p>
                                        <ul class="list-unstyled mb-0 w-max-content w-md-auto">
                                            <li class="d-flex align-items-center"><span
                                                    class="uil uil-check-circle text-success me-2"></span><span
                                                    class="text-body-tertiary fw-semibold">Fast</span></li>
                                            <li class="d-flex align-items-center"><span
                                                    class="uil uil-check-circle text-success me-2"></span><span
                                                    class="text-body-tertiary fw-semibold">Simple</span></li>
                                            <li class="d-flex align-items-center"><span
                                                    class="uil uil-check-circle text-success me-2"></span><span
                                                    class="text-body-tertiary fw-semibold">Responsive</span></li>
                                        </ul>
                                    </div>
                                    <div class="position-relative z-n1 mb-6 d-none d-md-block text-center mt-md-15"><img
                                            class="auth-title-box-img d-dark-none"
                                            src="{{ asset('assets/img/spot-illustrations/auth.png') }}"
                                            alt="" /><img class="auth-title-box-img d-light-none"
                                            src="{{ asset('assets/img/spot-illustrations/auth-dark.png') }}"
                                            alt="" />
                                    </div>
                                </div>
                                <div class="col mx-auto">
                                    <div class="auth-form-box">
                                        <div class="text-center mb-7"><a
                                                class="d-flex flex-center text-decoration-none mb-4" href="#">
                                                <div class="d-flex align-items-center fw-bolder fs-3 d-inline-block">
                                                    <img src="{{ asset('assets/img/icons/veer_logo.png') }}"
                                                        alt="Veer Logo" width="58" />
                                                </div>
                                            </a>
                                            <h3 class="text-body-highlight">Sign Up</h3>
                                            <p class="text-body-tertiary">Create your account today</p>
                                        </div>
                                        <div class="position-relative mt-4">
                                            <hr class="bg-body-secondary" />
                                            <div class="divider-content-center bg-body-emphasis">Use Your Email</div>
                                        </div>
                                        <form action='{{ route('register_staff') }}' method="post">
                                            @csrf
                                            <div class="mb-3 text-start"><label class="form-label"
                                                    for="name">Name</label><input class="form-control"
                                                    id="name" type="text" name='name'
                                                    placeholder="Name" value="{{ old('name') }}" />
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3 text-start"><label class="form-label"
                                                    for="mobile_no">Mobile No</label><input class="form-control"
                                                    id="mobile_no" type="text" name='mobile_no'
                                                    placeholder="Name" value="{{ old('mobile_no') }}" />
                                                @error('mobile_no')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3 text-start"><label class="form-label"
                                                    for="email">Email</label><input class="form-control"
                                                    id="email" type="email" name='email'
                                                    placeholder="xyz@veer.com" value="{{ old('email') }}" />
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="row g-3 mb-3">
                                                <div class="col-sm-6"><label class="form-label"
                                                        for="password">Password</label>
                                                    <div class="position-relative" data-password="data-password">
                                                        <input class="form-control form-icon-input pe-6"
                                                            id="password" type="password" name='password'
                                                            placeholder="Password" value="{{ old('password') }}" />
                                                        @error('password')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6"><label class="form-label"
                                                        for="confirmPassword">Confirm Password</label>
                                                    <div class="position-relative" data-password="data-password">
                                                        <input class="form-control form-icon-input pe-6"
                                                            id="confirmPassword" placeholder="Confirm Password"
                                                            name='confirm_password' type="text" />
                                                        @error('confirm_password')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check mb-3"><input class="form-check-input"
                                                    id="termsService" type="checkbox" /><label
                                                    class="form-label fs-9 text-transform-none" for="termsService">I
                                                    accept the <a href="#!">terms </a>and <a
                                                        href="#!">privacy policy</a></label></div><button
                                                class="btn btn-primary w-100 mb-3">Sign up</button>
                                            <div class="text-center"><a class="fs-9 fw-bold"
                                                    href="{{ route('login') }}">Sign
                                                    in to an existing account</a></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main><!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->



    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="{{ asset('assets/vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/list.js/list.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/dayjs/dayjs.min.js') }}"></script>
    <script src="{{ asset('assets/js/phoenix.js') }}"></script>
</body>


<!-- Mirrored from prium.github.io/phoenix/v1.18.0/pages/authentication/card/sign-up.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 12 Nov 2024 09:35:55 GMT -->

</html>
