<?php
setcontroller('jobposts');
$jobobj = new Jobposts();
$status = '';
$job_title = '';
$job_location_country = '';
$job_location_state = '';
$job_location_city = '';
$min_exp = '';
$max_exp = '';
$job_type = '';
$education = '';
$min_salary = '';
$max_salary = '';
$industry_types = array();
$get_job_functions = array();
$key_skills = array();
$company_name = '';
$contact_email = '';
$mobile_no = '';
$whatsapp_no = '';
$additional_links = '';
$button_name = 'Add Job';
$page_title = 'Add New Job';
if(isset($jobobj->jobdetail) && $jobobj->jobdetail != "")
{
    $status = $jobobj->jobdetail['status'];
    $job_title = stripslashes($jobobj->jobdetail['job_title']);
    $job_location_country = $jobobj->jobdetail['job_location_country'];
    $job_location_state = $jobobj->jobdetail['job_location_state'];
    $job_location_city = explode(',',$jobobj->jobdetail['job_location']);
    $min_exp = $jobobj->jobdetail['min_exp'];
    $max_exp = $jobobj->jobdetail['max_exp'];
    $job_type = $jobobj->jobdetail['job_type'];
    $education = stripslashes($jobobj->jobdetail['education']);
    $min_salary = $jobobj->jobdetail['min_salary'];
    $max_salary = $jobobj->jobdetail['max_salary'];
    $industry_types = explode(',',$jobobj->jobdetail['industry_types']);
    $get_job_functions = explode(',',$jobobj->jobdetail['job_functions']);
    $key_skills = explode(',',$jobobj->jobdetail['key_skills']);
    $company_name = stripslashes($jobobj->jobdetail['company_name']);
    $contact_email = $jobobj->jobdetail['contact_email'];
    $mobile_no = $jobobj->jobdetail['mobile_no'];
    $whatsapp_no = $jobobj->jobdetail['whatsapp_no'];
    $additional_links = $jobobj->jobdetail['additional_links'];
    $button_name = 'Update';
    $page_title = 'Edit Job';
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h4 class="title"><?php echo $page_title; ?></h4>
            </div>
            <div class="content">
                <?php
                if(isset($_SESSION['message']) && $_SESSION['message'] != "")
                {
                    echo $_SESSION['message'];unset($_SESSION['message']);
                }
                ?>
                <div class="form_container">
                    <form method="post" id="postjob">
                         <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status <em>*</em></label>
                                    <select class="form-control" name="status" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select status">
                                        <option value="">Select status</option>
                                        <option <?php echo $status == 'Active' ? 'selected' : ''; ?> value="Active">Active</option>
                                        <option <?php echo $status == 'Inactive' ? 'selected' : ''; ?> value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Job title <em>*</em></label>
                                    <input type="text" name="job_title" class="form-control" data-validation="required" data-validation-error-msg="Enter Job title" value="<?php echo $job_title; ?>" />
                                </div>
                            </div>
                            <?php $countries_list = $app->get_country(); ?>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Select country (Job location)<em>*</em></label>
                                    <select class="form-control" name="job_location_country" id="job_location_country" placeholder="Select country" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select country">
                                        <option value="">Select country</option>
                                        <?php if(!empty($countries_list))
                                        {
                                            foreach($countries_list as $countries)
                                            {
                                                ?><option <?php echo $job_location_country == $countries['id'] ? 'selected' : ''; ?> value="<?php echo $countries['id']; ?>"><?php echo $countries['name']; ?></option><?php
                                            }
                                        }?>                          
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?php if(isset($job_location_country) && is_numeric($job_location_country))
                                    {
                                        $state_list = $app->get_states($job_location_country);
                                    }
                                    ?>
                                    <label>Select state (Job location)<em>*</em></label>
                                    <select class="form-control" name="job_location_state" id="job_location_state" placeholder="Select state" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select state">
                                        <option value="">Select state</option>
                                        <?php if(!empty($state_list))
                                        {
                                            foreach($state_list as $states)
                                            {
                                                ?><option <?php echo $job_location_state == $states['id'] ? 'selected' : ''; ?> value="<?php echo $states['id']; ?>"><?php echo $states['name']; ?></option><?php
                                            }
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?php if(isset($job_location_state) && is_numeric($job_location_state))
                                    {
                                        $cities_list = $app->get_cities($job_location_state);
                                    }?>
                                    <label>Select city (Job location)<em>*</em></label>
                                    <select class="form-control" name="job_location[]" id="job_location" placeholder="Select city" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select city">
                                        <option value="">Select city</option>
                                        <?php if(!empty($cities_list))
                                        {
                                            foreach($cities_list as $cities)
                                            {
                                                ?><option <?php echo in_array($cities['id'], $job_location_city) ? 'selected' : ''; ?> value="<?php echo $cities['id']; ?>"><?php echo $cities['name']; ?></option><?php
                                            }
                                        }?>
                                     </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Experience <em>*</em></label>
                                    <div class="row">
                                        <div class="col-sm-6"><input placeholder="Min. Experience" class="form-control" type="number" name="min_exp" id="min_exp" data-validation="required" data-validation-error-msg="Enter Min. Experience" value="<?php echo $min_exp; ?>" /></div>
                                        <div class="col-sm-6"><input placeholder="Max. Experience" class="form-control" type="number" name="max_exp" id="max_exp" data-validation="required" data-validation-error-msg="Enter Max. Experience" value="<?php echo $max_exp; ?>" /></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Job Type <em>*</em></label>
                                    <select class="form-control" name="job_type" placeholder="Select Job type" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select Job type">
                                         <option value="">Select Job type</option>
                                         <option <?php echo $job_type == 'Full-Time' ? 'selected' : ''; ?> value="Full-Time">Full-Time</option>
                                         <option <?php echo $job_type == 'Part-Time' ? 'selected' : ''; ?> value="Part-Time">Part-Time</option>
                                         <option <?php echo $job_type == 'Freelancer' ? 'selected' : ''; ?> value="Freelancer">Freelancer</option>
                                         <option <?php echo $job_type == 'Fresher' ? 'selected' : ''; ?> value="Fresher">Fresher</option>
                                     </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Education <em>*</em></label>
                                    <input name="education" type="text" class="form-control" data-validation="required" data-validation-error-msg="Enter Education" value="<?php echo $education; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Salary Range (LPA) <em>*</em></label>
                                    <div class="row">
                                        <div class="col-sm-6"><input placeholder="Min. Salary" class="form-control" type="number" name="min_salary" id="min_salary" data-validation="required" data-validation-error-msg="Enter Min. Salary" value="<?php echo $min_salary; ?>" step="any" /></div>
                                        <div class="col-sm-6"><input placeholder="Max. Salary" class="form-control" type="number" name="max_salary" id="max_salary" data-validation="required" data-validation-error-msg="Enter Max. Salary" value="<?php echo $max_salary; ?>" step="any" /></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php $industry_type_list = $app->get_job_industry(); ?>
                                    <label>Industry <em>*</em></label>
                                    <select class="select_multi form-control" multiple name="industry_types[]" id="industry_type" placeholder="Select Industry type" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select Industry" data-live-search="true" data-live-search-placeholder="Select Industry" data-actions-box="true">
                                      <?php
                                            if(!empty($industry_type_list))
                                            {
                                                foreach($industry_type_list as $industry_type)
                                                {
                                                    ?>
                                                    <option <?php echo in_array($industry_type['id'], $industry_types) ? 'selected' : ''; ?> value="<?php echo $industry_type['id']; ?>"><?php echo $industry_type['name']; ?></option>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php $job_function_list = $app->get_job_functions(); ?>
                                    <label>Job Functions <em>*</em></label>
                                    <select class="select_multi form-control" multiple name="job_functions[]" id="job_functions" placeholder="Select Job Functions" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select Job Functions" data-live-search="true" data-live-search-placeholder="Select Job Functions" data-actions-box="true">
                                        <?php
                                            if(!empty($job_function_list))
                                            {
                                                foreach($job_function_list as $job_functions)
                                                {
                                                    ?>
                                                    <option <?php echo in_array($job_functions['id'],$get_job_functions) ? 'selected' : ''; ?> value="<?php echo $job_functions['id']; ?>"><?php echo $job_functions['job_function_name']; ?></option>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php $job_skills_list = $app->get_job_skills(); ?>
                                    <label>Key skills (keywords related to job) <em>*</em></label>
                                    <select class="select_multi form-control" multiple name="key_skills[]" id="key_skills" placeholder="Select key skills" data-validation="length" data-validation-length="min1" data-validation-error-msg="Select key skills" data-live-search="true" data-live-search-placeholder="Select key skills" data-actions-box="true">
                                        <?php
                                            if(!empty($job_skills_list))
                                            {
                                                foreach($job_skills_list as $job_skills)
                                                {
                                                    ?>
                                                    <option <?php echo in_array($job_skills['id'],$key_skills) ? 'selected' : '' ?> value="<?php echo $job_skills['id']; ?>"><?php echo $job_skills['skill_name']; ?></option>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Organization / company name <em>*</em></label>
                                    <input name="company_name" type="text" class="form-control" data-validation="required" data-validation-error-msg="Enter Organization / company name" data-validation="required" data-validation-error-msg="Enter Organization / company name" value="<?php echo $company_name; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contact email id <em>*</em></label>
                                    <input name="contact_email" type="text" class="form-control" data-validation="required" data-validation-error-msg="Enter Contact email id" data-validation="required" data-validation-error-msg="Enter Contact email id" value="<?php echo $contact_email; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Mobile no. <em>*</em></label>
                                    <input name="mobile_no" type="text" class="form-control" data-validation="required" data-validation-error-msg="Enter Mobile no" data-validation="required" data-validation-error-msg="Enter Mobile no" value="<?php echo $mobile_no; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>WhatsApp number (Optional)</label>
                                    <input name="whatsapp_no" type="text" class="form-control" value="<?php echo $whatsapp_no; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Additional links or URL (Optional)</label>
                                    <input name="additional_links" type="text" class="form-control" value="<?php echo $additional_links; ?>" />
                                </div>
                            </div>
                        </div>
                        <input name="add_job" value="<?php echo $button_name; ?>" type="submit" class="btn btn-info btn-fill pull-right" />
                        <a style="margin-right: 10px;" class="btn btn-default pull-right" href="?view=job_post">Back</a>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
