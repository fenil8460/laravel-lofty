<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Recover Password | Lofty Data -Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link href="{{ asset('assets/css/developer.css') }}" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{ asset('assets/css/developer.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app-modern.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('assets/css/app-modern-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />

</head>

<body class="loading authentication-bg" data-layout-config='{"darkMode":false}'>

    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <!-- Logo -->
                        <div class="card-header pt-4 pb-4 text-center bg-primary">
                            <a href="{{ route('login') }}">
                                <span><img src="assets/images/logo.png" alt="" height="18"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center mt-0 fw-bold">Reset Password</h4>
                                <p class="text-muted mb-4">Enter your email address and we'll send you an email with instructions to reset your password.</p>
                                <p class="text-muted mb-4">
                                    <!-- Session Status -->
                                    <x-auth-session-status class="mb-4" :status="session('status')" />
                                    <!-- Validation Errors -->
                                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                                </p>
                            </div>

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Email address</label>
                                    <input class="form-control" name="email" :value="old('email')" type="email" id="email" required="" placeholder="Enter your email">
                                </div>

                                <div class="mb-0 text-center">
                                    <button class="btn btn-primary" type="submit">Reset Password</button>
                                </div>
                            </form>
                        </div> <!-- end card-body-->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted">Back to <a href="{{ route('login') }}" class="text-muted ms-1"><b>Log In</b></a></p>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        2021 © Loft Data
    </footer>

    <!-- bundle -->
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>

</body>

</html>