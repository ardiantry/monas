<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="{{asset('logo.png')}}" /> 
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{asset('backend')}}/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend')}}/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend')}}/assets/css/authentication/form-2.css" rel="stylesheet" type="text/css" /> 
    <link rel="stylesheet" type="text/css" href="{{asset('backend')}}/assets/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="{{asset('backend')}}/assets/css/forms/switches.css">
</head>

<body class="form">
    <div class="form-container outer">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">
                        <div class="" id="show_success_alert"></div>
                        <h1 class="">Sign In</h1>
                        <form class="text-left" action="#" method="POST" id="login_form">
                            {{ csrf_field() }}
                            <div class="form">

                                <div id="username-field" class="field-wrapper input">
                                    <label for="username">User Name</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                    <input id="user_name" name="user_name" type="text" class="form-control" placeholder="User Name">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div id="password-field" class="field-wrapper input">
                                    <div class="d-flex justify-content-between">
                                        <label for="password">Password</label>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    <input id="password" name="password" id="password" type="password" class="form-control" placeholder="Password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <div class="invalid-feedback"></div>
                                </div>
                              <!--   <div class="mb-3">
                                    <a class="text-primary" href="{{ url('/forgot') }}">Lupa Password ?</a>
                                </div> -->
                                <div class="d-sm-flex justify-content-between">
                                    <div class="field-wrapper">
                                        <input id="login_btn" type="submit" value="login" class="btn btn-primary">
                                    </div>
                                </div>
                                <p class="signup-link">Register ? <a href="{{ url('/register') }}">Click</a></p>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{asset('backend')}}/assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="{{asset('backend')}}/bootstrap/js/popper.min.js"></script>
    <script src="{{asset('backend')}}/bootstrap/js/bootstrap.min.js"></script>

    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="{{asset('backend')}}/assets/js/authentication/form-2.js"></script>
    <script src="{{asset('js')}}/function.js"></script>
    <script>
        $(document).ready(function()
        {
            $('#login_form').submit(function(e) {
                e.preventDefault();
                $('#login_btn').val('please wait...');
                $.ajax({
                    url: '/login',
                    method: 'post',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 400) {
                            console.log(res.messages);
                            showError('email', res.messages.email);
                            showError('password', res.messages.password);
                            $('#login_btn').val('login');
                        } else if (res.status == 401) {
                            // console.log(res.messages);
                            $('#login_btn').val('login');
                            $('#show_success_alert').html(showMessage('danger', res.messages));
                            $('#register_form')[0].reset();
                            removeValidationClasses('#login_form')
                        } else {
                            if (res.status == 200 && res.messages == 'success') {
                                window.location = '/dashboard';
                            }
                        }

                    }
                })
            });

        });
    </script>
</body>

</html>