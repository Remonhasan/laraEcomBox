<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>e-Dove | Admin Registration</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('admin/assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/components.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/colors.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{ asset('admin/assets/js/plugins/loaders/pace.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/assets/js/core/libraries/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/assets/js/core/libraries/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/assets/js/plugins/loaders/blockui.min.js') }}"></script>
    <!-- /core JS files -->


    <!-- Theme JS files -->
    <script type="text/javascript" src="{{ asset('admin/assets/js/core/app.js') }}"></script>
    <!-- /theme JS files -->

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>

</head>

<body>

    <!-- Main navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light align-content-end">
        <div class="container-fluid">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="{{ url('/en/' . 'admin-register') }}"
                    class="{{ 'en' === app()->getLocale() ? 'active' : '' }}  btn btn-outline-info btn-sm">
                    {{ __('English') }}
                </a>
                <a href="{{ url('/bn/' . 'admin-register') }}"
                    class="{{ 'bn' === app()->getLocale() ? 'active' : '' }}  btn btn-outline-info btn-sm">
                    {{ __('বাংলা') }}
                </a>
            </div>
        </div>
    </nav>
    <!-- /main navbar -->


    <!-- Page container -->
    <div class="page-container login-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Content area -->
                <div class="content">

                    <!-- Simple login form -->
                    <form action="{{ route('admin.register') }}" method="post">
                        @csrf
                        <div class="panel panel-body login-form">
                            <div class="text-center">
                                <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i>
                                </div>
                                <h5 class="content-group">{{ __('Register a new account') }} <small
                                        class="display-block">{{ __('Enter your credentials below') }}</small></h5>

                                <div class="content-group">
                                    @if (Session::has('error'))
                                        <strong class="text-danger"> {{ session::get('error') }}</strong>
                                    @endif
                                </div>

                            </div>

                            <div class="form-group has-feedback has-feedback-left">
                                <label for="email" class="font-weight-bold ml-1">{{ __('Username') }}</label>
                                <input type="text" name="name" class="form-control" autocomplete="off"
                                    placeholder="{{ __('Username') }}">
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                            </div>
                            <div class="form-group has-feedback has-feedback-left">
                                <label for="email" class="font-weight-bold ml-1">{{ __('Email') }}</label>
                                <input type="email" name="email" class="form-control" autocomplete="off"
                                    placeholder="{{ __('Email') }}">
                                <div class="form-control-feedback">
                                    <i class="icon-envelop text-muted"></i>
                                </div>
                            </div>

                            <div class="form-group has-feedback has-feedback-left">
                                <label for="email" class="font-weight-bold ml-1">{{ __('Password') }}</label>
                                <input type="password" name="password" class="form-control" autocomplete="off"
                                    placeholder="{{ __('Password') }}">
                                <div class="form-control-feedback">
                                    <i class="icon-lock2 text-muted"></i>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">{{ __('Register') }}<i
                                        class="icon-circle-right2 position-right"></i></button>
                            </div>

                            <div class="text-center">
                                <a href="login_password_recover.html">{{ __('Forgot password?') }}</a>
                                <span>
                                    <a href="{{ route('login.form') }}">{{ __('Login') }}</a>
                                </span>
                            </div>
                        </div>
                    </form>
                    <!-- /simple login form -->


                    <!-- Footer -->
                    <div class="footer text-muted">
                        &copy; 2022. <a href="#">{{ __('Developed') }}</a> {{ __('by') }} <a href="http://themeforest.net/user/Kopyov" target="_blank">{{ __('Remon Hasan Apu') }}</a>
                    </div>
                    <!-- /footer -->

                </div>
                <!-- /content area -->

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

    </div>
    <!-- /page container -->

</body>

</html>
