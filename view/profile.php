<?php
    setcontroller('account');
    $profile = new Account();
    
    $userdata = '';
    $get_job_functions = array();
    if(isset($_SESSION['userdata']) && !empty($_SESSION['userdata']))
    {
	$userdata = $_SESSION['userdata'];
	$get_job_functions = explode(',',$userdata['job_function_area']);
    }
?>
<section class="allcontainer">
    <div class="container">
       <div class="row">
		<div class="threecolumn">
		    <div class="leftbar"><?php include('sidebar.php'); ?></div>
		    <div class="middlebar">
			    <div class="rightbarinner">
				<h2 class="pagetitleinner">My Profile</h2>
				<?php
				if(isset($_SESSION['message']))
				{
				    echo $_SESSION['message'];
				    unset($_SESSION['message']);
				}
				?>
				<form method="post" enctype="multipart/form-data">
				    <div class="row">
					
					<div class="col-md-3">
					    <div class="profilepicchange"><img src="<?php echo $profile->get_profile_photo(); ?>" alt="User" /></div>
					    <div class="form-group">
						<input type="file" name="profile_photo" id="profile_photo" />
					    </div>
					</div>
					<div class="col-md-9">
					    <div class="profile_form">
						<div class="row">
						    <div class="col-md-6">
							<div class="form-group">
							    <label>Sponser / Referrer</label>
							    <input value="<?php echo $userdata['ref_id'] == 0 ? '' : $userdata['ref_id'] ?>" disabled="disabled" class="form-control"  />
							</div>
						    </div>
						    <div class="col-md-6">
							<div class="form-group">
							    <label>Gender <em>*</em></label>
							    <select name="gender" class="form-control" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select Gender">
								<option value="">Select Gender</option>
								<option <?php echo $userdata['gender'] == 'Male' ? 'selected="selected"' : '' ?> value="Male">Male</option>
								<option <?php echo $userdata['gender'] == 'Female' ? 'selected="selected"' : '' ?> value="Female">Female</option>
							    </select>
							</div>
						    </div>
						    <div class="col-md-6">
							<div class="form-group">
							    <label>Full Name <em>*</em></label>
							    <input class="form-control" data-validation="required" data-validation-error-msg="Enter your full name" type="text" name="fullname" id="fullname" value="<?php echo stripslashes($userdata['fullname']); ?>" />
							</div>
						    </div>
						    <div class="col-md-6">
							<div class="form-group">
							    <label>Email</label>
							    <input disabled="disabled" class="form-control" value="<?php echo $userdata['email']; ?>" />
							</div>
						    </div>
						    <div class="col-md-6">
							<div class="form-group">
							    <label>Experience <em>*</em></label>
							    <div class="row">
								<div class="col-md-6">
								    <select name="experience_year" class="form-control" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select Experience Year">
									<option value="">Select Year</option>
									<?php
									$expyear = '';
									$expmonth = '';
									if($userdata['experience_year_month'] != '')
									{
									    $experience_year_month = explode(',',$userdata['experience_year_month']);
									    $expyear = $experience_year_month[0];
									    $expmonth = $experience_year_month[1];
									}
									for($i=0;$i<=15;$i++)
									{
									    ?><option <?php echo ($expyear != '' && $expyear == $i) ? 'selected="selected"' : ''  ?> value="<?php echo $i; ?>"><?php echo $i; ?></option><?php
									}?>
								    </select>
								</div>
								<div class="col-md-6">
								    <select name="experience_month" class="form-control" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select Experience Month">
									<option value="">Select Month</option>
									<?php
									for($i=0;$i<=11;$i++)
									{
									    ?><option <?php echo ($expmonth != '' && $expmonth == $i) ? 'selected="selected"' : ''  ?> value="<?php echo $i; ?>"><?php echo $i; ?></option><?php
									}?>
								    </select>
								</div>
							    </div>
							</div>
						    </div>
						    <div class="col-md-6">
							<div class="form-group">
							    <label>Education <em>*</em></label>
							    <input class="form-control" data-validation="required" data-validation-error-msg="Enter Education" type="text" name="education" id="education" value="<?php echo stripslashes($userdata['education']) ?>" />
							</div>
						    </div>
						    <div class="col-md-6">
							<?php $job_function_list = $profile->get_job_functions(); ?>
							<div class="form-group">
							    <label>Job Functions <em>*</em></label>
							    <select name="job_function_area" class="select_multi form-control" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select Job Function" data-live-search="true" data-live-search-placeholder="Job Functions" data-actions-box="true">
								<option value="">Select Job Functions</option>
								<?php
								    if(!empty($job_function_list))
								    {
									foreach($job_function_list as $job_functions)
									{
									    ?>
									    <option <?php echo in_array($job_functions['id'],$get_job_functions) ? 'selected' : ''; ?> value="<?php echo $job_functions['id'] .'|'.$job_functions['job_function_name']; ?>"><?php echo $job_functions['job_function_name']; ?></option>
									    <?php
									}
								    }
								?>
							    </select>
							    
							</div>
						    </div>
						    <div class="col-md-6">
							<div class="form-group">
							    <?php $job_skills_list = $profile->get_job_skills();
							    $key_skills = array();
							    if(isset($userdata['key_skills']))
							    {
								$key_skills = explode(',',$userdata['key_skills']);
							    }
							    ?>
							    <label>Key Skills <em>*</em></label>
							    <select name="key_skills[]" class="select_multi form-control" multiple data-validation="length" data-validation-length="min1" data-validation-error-msg="Select Key Skills" data-live-search="true" data-live-search-placeholder="Key Skills" title="Select Key Skills">
								<?php
								    if(!empty($job_skills_list))
								    {
									foreach($job_skills_list as $job_skills)
									{
									    ?>
									    <option <?php echo in_array($job_skills['id'],$key_skills) ? 'selected' : '' ?> value="<?php echo $job_skills['id'] .'|'. $job_skills['skill_name']; ?>"><?php echo $job_skills['skill_name']; ?></option>
									    <?php
									}
								    }
								?>
							    </select>
							</div>
						    </div>
						    <div class="col-md-6">
							<div class="form-group">
							    <label>Annual Salary <em>*</em></label>
							    <input class="form-control" type="number" name="salary" id="salary" data-validation="required" data-validation-error-msg="Enter Salary" value="<?php echo $userdata['salary'] ?>" step="any" />
							</div>
						    </div>
						    <div class="col-md-6">
							<div class="form-group">
							    <label>Contact Number <em>*</em></label>
							    <input class="form-control" data-validation="required" data-validation-error-msg="Enter Contact Number" type="text" name="phone_no" id="phone_no" value="<?php echo $userdata['phone_no']; ?>" />
							</div>
						    </div>
						    <div class="col-md-6">
							<div class="form-group">
							    <?php $countries_list = $profile->get_country(); ?>
							    <label>Country <em>*</em></label>
							    <select id="country" name="country" class="select_multi form-control" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select country" data-live-search="true" data-live-search-placeholder="Select country" data-actions-box="true">
								<option value="">Select country</option>
								<?php if(!empty($countries_list))
								{
								    foreach($countries_list as $countries)
								    {
									?><option <?php echo $userdata['country'] == $countries['id'] ? 'selected="selected"': '' ?> value="<?php echo $countries['id']; ?>"><?php echo $countries['name']; ?></option><?php
								    }
								}?>
							    </select>
							</div>
						    </div>
						    <div class="col-md-6">
							<div class="form-group">
							    <?php if(isset($userdata['country']) && is_numeric($userdata['country']))
							    {
								$state_list = $profile->get_states($userdata['country']);
							    }
							    ?>
							    <label>State <em>*</em></label>
							    <select name="state" id="state" class="select_multi form-control" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select state" data-live-search="true" data-live-search-placeholder="Select state" data-actions-box="true">
								<option value="">Select state</option>
								<?php if(!empty($state_list))
								{
								    foreach($state_list as $states)
								    {
									?><option <?php echo $userdata['state'] == $states['id'] ? 'selected' : ''; ?> value="<?php echo $states['id']; ?>"><?php echo $states['name']; ?></option><?php
								    }
								}?>
							    </select>
							</div>
						    </div>
						    <div class="col-md-6">
							<div class="form-group">
							    <?php if(isset($userdata['state']) && is_numeric($userdata['state']))
							    {
								$cities_list = $profile->get_cities($userdata['state']);
							    }?>
							    <label>City <em>*</em></label>
							    <select name="location" id="location" class="select_multi form-control" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select city" data-live-search="true" data-live-search-placeholder="Select city" data-actions-box="true">
								<option value="">Select city</option>
								<?php if(!empty($cities_list))
								{
								    foreach($cities_list as $cities)
								    {
									?><option <?php echo ($userdata['location'] == $cities['id']) ? 'selected' : ''; ?> value="<?php echo $cities['id']; ?>"><?php echo $cities['name']; ?></option><?php
								    }
								}?>
							    </select>
							</div>
						    </div>
						    <div class="col-md-6">
							<?php
							$required_param = 'data-validation="required"';
							$show_resume_field = 'display:block';
							if($userdata['resume'] != '')
							{
							    $show_resume_field = 'display:none';
							    $required_param = '';
							    ?><a class="resume_update btn btn-success" href="javascript:void(0)">Update resume</a><?php
							}
							?>
							<div class="form-group" style="<?php echo $show_resume_field ?>">
							    <label>Upload Resume <em>*</em></label>
							    <input type="file" name="resume" id="resume" <?php echo $required_param; ?> />
							</div>
						    </div>
						    <div class="col-sm-6">
							<div class="captchabox profile_recaptcha">
							    <div class="g-recaptcha" data-sitekey="<?php echo $grecaptcha_key; ?>"></div>
							    <input type="hidden" data-validation="recaptcha" data-validation-recaptcha-sitekey="<?php echo $grecaptcha_key; ?>">
							</div>
						    </div>
						    <div class="col-sm-6"><div class="submitbutton profile_button"><input class="register btn btn-primary" type="submit" name="update_profile" value="Update profile" /></div></div>
						</div>
					    </div>
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