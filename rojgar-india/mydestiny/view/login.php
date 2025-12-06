<?php
if($app->checklogin() === true)
{
    ?><script>window.location='?view=dashboard'</script><?php
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Foursis Admin Panel - Login</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
    <style type="text/css">
        body{background-color: #34495E;font-family: "Lato";font-size:16px;}
        .loginbox_wrapper{width: 400px;max-width: 100%;margin: 0 auto;box-shadow:0px 2px 4px rgba(0,0,0,0.3);position: relative;z-index: 1;background-color: rgba(203, 203, 210, 0.15);background-image:url(assets/img/full-screen-image-3.jpg);background-size: cover;text-align: center;border-radius:5px;overflow: hidden;}
        .login_overaly{background-color: rgba(42, 54, 69,0.97);padding: 20px;border: 1px solid rgba(231,231,231,0.3);border-radius:5px}
        .logo{text-align: center;margin-top: 70px;margin-bottom: 30px;}
        .login_title h1{color: #e7e7e7;margin-top: 10px;font-weight:600;margin-bottom: 30px;}
        .login_title h1 span{color: #2980b9}
        .login_form{padding: 0px 10px;}
        .login_form .form-group{margin-bottom: 25px;}
        .login_form .btn-default{background-color: #2980b9;width: 100%;color: #ffffff;border: 1px solid #2980b9;}
        .form-error{font-size: 12px;text-align: left;background-color: #e7e7e7;border: 1px solid #ff0000;color: #ff0000 !important;padding: 2px 5px;margin-top: 0px;margin-bottom: 5px;}
    </style>
</head>
<body>
    <div class="logo"><img width="175" src="assets/img/logo.png" alt="Foursis" /></div>
    <div class="loginbox_wrapper">
        <div class="login_overaly">
            <div class="login_title"><h1>Welcome <span>Admin</span></h1></div>
            <form method="post">
                <div class="login_form">
                    <?php
                    if(isset($_SESSION['message']))
                    {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    }
                    ?>
                    <div class="form-group">
                        <input type="text" data-validation="required" data-validation-error-msg="Enter Username" class="form-control" name="username" id="username" placeholder="Username" />
                    </div>
                    <div class="form-group">
                        <input type="password" data-validation="required" data-validation-error-msg="Enter Password" class="form-control" name="password" id="password" placeholder="Password" />
                    </div>
                    <div class="form-group">
                        <input class="btn btn-default" name="login" type="submit" value="Login" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
<!--   Core JS Files   -->
<script src="assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script>
$.validate({
    modules : 'security',
    validateHiddenInputs : true,
    scrollToTopOnError : false
});
</script>
</html>