<!doctype html>
<html class="no-js" lang="en">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Login | EMS</title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="icon" href="{{ asset('img/logo/favicon-zetasol.png') }}" type="image/x-icon" />

        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800" rel="stylesheet">
        
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/ionicons/dist/css/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/icon-kit/dist/css/iconkit.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}">
        <link rel="stylesheet" href="{{ asset('dist/css/theme.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <script src="{{ asset('src/js/vendor/modernizr-2.8.3.min.js') }}"></script>
        <style type="text/css">
            .login-desc-img {
                position: absolute;
                bottom: 15px;
                right: 15px;
            }
        </style>
    </head>

    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <div class="auth-wrapper" id="auth-wrapper">
            <div class="container-fluid h-100">
                <div class="row flex-row h-100">
                    <div class="col-md-8 login-form">
                        <div class="col-xl-5 col-lg-5 col-md-5 login-div">
                            @include('include.message')
                            <div class="authentication-form">                               
                                <h5 class="login-welcometxt">Hello, Welcome back! </h5>
                                <div class="logo-centered">
                                 <img height="55" src="{{ asset('img/auth/login-icon.svg') }}" alt="Zeta" >
                                </div>
                                <form method="POST" action="{{ route('login') }}">
                                @csrf
                                    <div class="form-group">
                                        <input id="email" type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        <i class="ik ik-user"></i>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                        <i class="ik ik-lock"></i>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col text-left">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="item_checkbox" name="item_checkbox" value="option1">
                                                <span class="custom-control-label">&nbsp;Remember Me</span>
                                            </label>
                                        </div>
                                        <!-- <div class="col text-right">
                                            <a class="btn text-danger" href="{{url('password/forget')}}">
                                                {{ __('Forgot Password?') }}
                                            </a>
                                        </div> -->
                                    </div>
                                    <div class="sign-btn text-center">
                                        <button class="btn btn-custom">Sign In</button>
                                    </div>                               
                                </form>
                            </div>
                        </div>
                     </div>

                     <div class="col-md-4 login-desc">
                        <div class="login-about">
                            <div class="logo">
                               <img height="48" src="{{ asset('img/auth/EMS-zeta.png') }}" alt="Zeta" >
                            </div>
                            <p>"We are providing a solution related to the organizational workday, attendance & payroll matters by launching Employee Management System Software, which interprets and conducts to manage employee details in an efficient manner." </p>

                             <div class="login-desc-img">
                               <img  src="{{ asset('img/auth/ems-desc-img.png') }}" alt="Zeta" >
                            </div>
                        </div>
                     </div>
                </div>
            </div>
        </div>
<!-- <?php $user_flag //= Session::get('user_flag');?>
  <input type="hidden" id="user_flag" name="" value='<?php //echo $user_flag; ?>'>  -->       
        <script src="{{ asset('src/js/vendor/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('plugins/popper.js/dist/umd/popper.min.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('plugins/screenfull/dist/screenfull.js') }}"></script>
        
    </body>
</html>

<!-- <script type="text/javascript">
        var user_flag = document.getElementById("user_flag").value;
        if(user_flag == 0 ){
        document.getElementById('auth-wrapper').style.backgroundColor = '#17a2b8' ; 
        }
        else if(user_flag == 1 ){
        document.getElementById('auth-wrapper').style.backgroundColor = '#408e6d' ; 
    }
</script> -->