<?php
    setcontroller('login');
    $login = new Login();
?>
<section class="allcontainer">
    <div class="container">
        <div class="formcontainer logincontainer">
            
            <?php if(isset($_GET['account_id']) && is_numeric($_GET['account_id']) && isset($_GET['resetkey']))
            {
                ?>
                    <h2 class="formtitle">Reset Your <span>Password</span></h2>
                    <?php
                    $getdata = $login->get_forgot_code_key($_GET['account_id'],$_GET['resetkey']);
                    if(!empty($getdata))
                    {
                        ?>
                        <form method="post">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input class="form-control" data-validation="length" data-validation-length="min8" data-validation-error-msg="Enter new password" type="password" name="password" id="password" placeholder="New password" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input class="form-control" data-validation="confirmation" data-validation-confirm="password" type="password" name="cpassword" id="cpassword" placeholder="Confirm new password" data-validation-error-msg="Password not match" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="captchabox">
                                        <div class="g-recaptcha" data-sitekey="<?php echo $grecaptcha_key; ?>"></div>
                                        <input type="hidden" data-validation="recaptcha" data-validation-recaptcha-sitekey="<?php echo $grecaptcha_key; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="fullbutton"><input class="register btn btn-primary" type="submit" name="change_password" value="Change Password" /></div>
                                </div>
                            </div>    
                        </form>
                        <?php
                    }
                    else
                    {
                        echo '<div class="message_box" style="font-size:16px;">Invalid Password reset key or Password reset code is expire. <a href="forgot-password">Click here</a> to try again</div>';
                    }
                    ?>
                    
                <?php
            }
            else
            { ?> 
            
                <h2 class="formtitle">Forgot <span>Password ?</span></h2>
                <?php
                if(isset($_SESSION['message']))
                {
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                }
                ?>
                <form method="post">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input class="form-control" data-validation="email" data-validation-error-msg="Enter your email" type="text" name="email" id="email" placeholder="Email" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="captchabox">
                                <div class="g-recaptcha" data-sitekey="<?php echo $grecaptcha_key; ?>"></div>
                                <input type="hidden" data-validation="recaptcha" data-validation-recaptcha-sitekey="<?php echo $grecaptcha_key; ?>">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="fullbutton"><input class="register btn btn-primary" type="submit" name="forgot_password" value="Recover" /></div>
                        </div>
                        <div class="col-sm-6"><a class="linkbutton" href="register">Register</a></div>
                        <div class="col-sm-6"><a class="linkbutton" href="login">Login</a></div>
                    </div>
                </form>
            <?php
            }?>
        </div>
    </div>
</section>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script>
$.validate({
    modules : 'security',
    validateHiddenInputs : true,
    scrollToTopOnError : false
});
</script>