<?php $regobj = new Register(); ?>
<section class="allcontainer">
    <div class="container">
        <div class="formcontainer">
            <?php if(isset($_GET['account_id']) && is_numeric($_GET['account_id']) && isset($_GET['activationkey']))
            {
                ?>
                <h2 class="formtitle">Email <span>Verification</span></h2>
                <div class="message_box">
                <?php
                    $verify_status = $regobj->email_verification($_GET['account_id'],$_GET['activationkey']);
                    if($verify_status == 'active')
                    {
                        echo 'Your account is already activated. <a href="login">Click here</a> to Login';
                    }
                    elseif($verify_status == 'code_expire')
                    {
                        echo 'Email Verification code has been expired. <br /><a href="resend-link">Click here</a> to resend activation link.';
                    }
                    elseif($verify_status == 'success')
                    {
                        echo 'You have successfully verified your account. <a href="login">Click here</a> to Login';
                    }
                    else{
                        echo 'Invalid account activation link. Or activation link might be changed.';
                    }
                ?>
                </div>
                <?php
            }
            else
            {?>
                <h2 class="formtitle">Get Registered for <span>Foursis</span></h2>
                <?php
                if(isset($_SESSION['message']))
                {
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                }
                ?>
                <form method="post">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control" type="text" name="refid" id="refid" placeholder="Entre your referrer Id" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select name="gender" class="form-control" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select Gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control" data-validation="required" data-validation-error-msg="Enter your full name" type="text" name="fullname" id="fullname" placeholder="Enter your full name" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control" data-validation="email" data-validation-error-msg="Enter your email" type="text" name="email" id="email" placeholder="Enter your email" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control" data-validation="length" data-validation-length="min8" data-validation-error-msg="Enter your password" type="password" name="password" id="password" placeholder="Enter your password" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control" data-validation="confirmation" data-validation-confirm="password" type="password" name="cpassword" id="cpassword" placeholder="Confirm your password" data-validation-error-msg="Password not match" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php $job_function_list = $regobj->get_job_functions(); ?>
                                <select name="job_function_area" class="select_multi form-control" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select Job Function" data-live-search="true" data-live-search-placeholder="Job Functions" data-actions-box="true">
                                    <option value="">Select Job Functions</option>
                                    <?php
                                        if(!empty($job_function_list))
                                        {
                                            foreach($job_function_list as $job_functions)
                                            {
                                                ?>
                                                <option value="<?php echo $job_functions['id']; ?>"><?php echo $job_functions['job_function_name']; ?></option>
                                                <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php $countries_list = $regobj->get_country(); ?>
                                <select id="country" name="country" class="select_multi form-control" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select country" data-live-search="true" data-live-search-placeholder="Select country" data-actions-box="true">
                                    <option value="">Select country</option>
                                    <?php if(!empty($countries_list))
                                    {
                                        foreach($countries_list as $countries)
                                        {
                                            ?><option value="<?php echo $countries['id']; ?>"><?php echo $countries['name']; ?></option><?php
                                        }
                                    }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select name="state" id="state" class="select_multi form-control" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select state" data-live-search="true" data-live-search-placeholder="Select state" data-actions-box="true">
                                    <option value="">Select state</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select name="location" id="location" class="select_multi form-control" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select city" data-live-search="true" data-live-search-placeholder="Select city" data-actions-box="true">
                                    <option value="">Select city</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control" data-validation="required" data-validation-error-msg="Enter your contact number" type="text" name="contactnumber" id="contactnumber" placeholder="Enter your contact number" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="captchabox">
                                <div class="g-recaptcha" data-sitekey="<?php echo $grecaptcha_key; ?>"></div>
                                <input type="hidden" data-validation="recaptcha" data-validation-recaptcha-sitekey="<?php echo $grecaptcha_key; ?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="submitbutton"><input class="register btn btn-primary" type="submit" name="register" value="Register" /></div>
                        </div>
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