<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Register</title>
    <link rel="icon" type="image/x-icon" href="{{asset('backend')}}/assets/img/favicon.ico" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{asset('backend')}}/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend')}}/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend')}}/assets/css/authentication/form-2.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="{{asset('backend')}}/assets/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="{{asset('backend')}}/assets/css/forms/switches.css">
</head>

<body class="form">
    <div class="form-container outer">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content" style="margin: 50px;">
                        <div class="" id="show_success_alert"></div>
                        <h1 class="">Register</h1>
                        <form class="text-left" action="#" method="POST" id="register_form">
                            {{ csrf_field() }}
                                <div class="form-group row">
                                    <div class="col-6"> 
                                        <label for="name">First name</label> 
                                        <input id="name" name="name" type="text" class="form-control" placeholder="First Name">
                                        <div class="invalid-feedback"></div>        
                                    </div>
                                    <div class="col-6">
                                        <label for="last_name">Last name</label> 
                                        <input id="last_name" name="last_name" type="text" class="form-control" placeholder="Last Name">
                                        <div class="invalid-feedback"></div> 
                                    </div>
                                </div>
                          
                                <div class="form-group row"> 
                                    <div class="col-6">
                                        <label for="tgl_lahir">Date of birth</label> 
                                        <input id="tgl_lahir" name="tgl_lahir" type="date" class="form-control" placeholder="Date of birth">
                                        <div class="invalid-feedback"></div>  
                                    </div>
                                     <div class="col-6">
                                        <label for="tpt_lahir">Place of birth</label> 
                                        <input id="tpt_lahir" name="tpt_lahir" type="text" class="form-control" placeholder="Date of birth">
                                        <div class="invalid-feedback"></div>        
                                        
                                    </div> 
                                </div>
                                <div class="form-group">  
                                        <label for="nip">NIP</label> 
                                        <input id="nip" name="nip" type="number" class="form-control" placeholder="NIP">
                                        <div class="invalid-feedback"></div>  
                                </div>
                                <div class="form-group">
                                        <label for="no_tlp">Phone number</label> 
                                        <input id="no_tlp" name="no_tlp" type="text" class="form-control" placeholder="Phone number">
                                        <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group">  
                                        <label for="user_name">User name</label> 
                                        <input id="user_name" name="user_name" type="text" class="form-control" placeholder="User Name">
                                        <div class="invalid-feedback"></div>  
                                    </div>
                                     <div class="form-group">
                                        <label for="email">Email</label> 
                                        <input id="email" name="email" type="text" class="form-control" placeholder="Email">
                                        <div class="invalid-feedback"></div>    
                                </div>

                                <div class="form-group row"> 
                                    <div class="col-6">
                                        <label for="password">Password</label> 
                                        <input id="password" name="password" type="password" class="form-control" placeholder="Password">
                                        <div class="invalid-feedback"></div>  
                                    </div>
                                     <div class="col-6">
                                        <label for="passwordconfirm">password confirm</label> 
                                        <input id="passwordconfirm" name="passwordconfirm" type="password" class="form-control" placeholder="Password confirm">
                                        <div class="invalid-feedback"></div>        
                                        
                                    </div> 
                                </div>
                                 <div class="d-sm-flex justify-content-between">
                                    <div class="field-wrapper">
                                        <button   type="submit"  class="btn btn-primary">Register</button>
                                    </div>
                                </div>
                                <p class="signup-link">Login ? <a href="{{ url('/login') }}">Click</a></p>
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
            $('#register_form').submit(function(e) {
                console.log('test');
            e.preventDefault();
            $('#register_btn').val('please wait...');
            $('#register_btn').attr('disabled', 'disabled');
            $.ajax({
                url: '/register',
                method: 'post',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status == 400) {
                        var objKey=Object.keys(res.messages);
                        console.log(objKey);
                        for(let err_msg of objKey)
                        {
                        showError(err_msg,res.messages[err_msg][0]);

                        }
                        // alert(res.messages.name);
                         
                        $('#register_btn').removeAttr('disabled');
                        $('#register_btn').val('register');
                    } else if (res.status == 200) {
                        $('#show_success_alert').html(showMessage('success', res.messages));
                        $('#register_form')[0].reset();
                        removeValidationClasses('#register_form')
                        $('#register_btn').removeAttr('disabled');
                        $('#register_btn').val('register');
                    }

                }
            })
            });
     });
            
    </script>

</body>

</html>