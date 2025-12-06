<section class="allcontainer">
    <div class="container">
        <div class="formcontainer logincontainer">
            <h2 class="formtitle">Login to <span>Foursis</span></h2>
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
                        <div class="form-group">
                            <input class="form-control" data-validation="required" data-validation-error-msg="Enter your password" type="password" name="password" id="password" placeholder="Password" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="captchabox">
                            <div class="g-recaptcha" data-sitekey="<?php echo $grecaptcha_key; ?>"></div>
                            <input type="hidden" data-validation="recaptcha" data-validation-recaptcha-sitekey="<?php echo $grecaptcha_key; ?>">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="fullbutton"><input class="register btn btn-primary" type="submit" name="login" value="Login" /></div>
                    </div>
                    <div class="col-sm-6"><a class="linkbutton" href="register">Register</a></div>
                    <div class="col-sm-6"><a class="linkbutton" href="forgot-password">Forgot Password?</a></div>
                    <div class="col-sm-12"><a class="linkbutton" href="resend-link">Resend Activation Link</a></div>
                </div>
            </form>
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