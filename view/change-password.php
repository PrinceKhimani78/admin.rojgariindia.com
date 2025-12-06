<?php
    setcontroller('account');
    $profile = new Account();
?>
<section class="allcontainer">
    <div class="container">
       <div class="row">
		<div class="threecolumn">
		    <div class="leftbar"><?php include('sidebar.php'); ?></div>
		    <div class="middlebar">
			    <div class="rightbarinner">
				<h2 class="pagetitleinner">Change Password</h2>
				<?php
				if(isset($_SESSION['message']))
				{
				    echo $_SESSION['message'];
				    unset($_SESSION['message']);
				}
				?>
				<form method="post">
				    <div class="profile_form">
					<div class="small_container" style="width: 400px;max-width: 100%;margin: 0 auto;">
					    <div class="form-group">
						<label>Old / Current Password <em>*</em></label>
						<input class="form-control" data-validation="length" data-validation-length="min8" data-validation-error-msg="Enter your old password" type="password" name="old_password" id="old_password" />
					    </div>
					    <div class="form-group">
						<label>New Password <em>*</em></label>
						<input class="form-control" data-validation="length" data-validation-length="min8" data-validation-error-msg="Enter your new password" type="password" name="password" id="password" />
					    </div>
					    <div class="form-group">
						<label>Confirm New Password <em>*</em></label>
						<input class="form-control" data-validation="confirmation" data-validation-confirm="password" type="password" name="cpassword" id="cpassword" data-validation-error-msg="Password not match" />
					    </div>
					    <div class="captchabox profile_recaptcha">
						<div class="g-recaptcha" data-sitekey="<?php echo $grecaptcha_key; ?>"></div>
						<input type="hidden" data-validation="recaptcha" data-validation-recaptcha-sitekey="<?php echo $grecaptcha_key; ?>">
					    </div>
					    <div class="submitbutton profile_button"><input class="register btn btn-primary" type="submit" name="change_password" value="Change password" /></div>
					</div>
				    </div>
				</form>
			    </div>
		    </div>
                </div>
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