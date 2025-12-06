<?php $jobwallobj = new Jobwall();

$limit = 25;

if (!isset($_GET['page'])) {
    $page = 1;
} else{
    $page = $_GET['page'];
}

$jobwall = $jobwallobj->get_jobwall($limit,$page);
$total_pages = $jobwallobj->total_pages;
?>
<section class="allcontainer">
    <div class="container">
       <div class="row">
		<div class="threecolumn">
                    <?php
                    if(isset($_SESSION['uid']) && $_SESSION['uid'] != ''){
                        setcontroller('account');
                    ?>
		    <div class="leftbar"><?php include('sidebar.php');?></div>
                    <?php } ?>
                    <div class="middlebar">
			<div class="search_box">
			    <form method="post">
				<div class="row">
				    <div class="col-sm-6">
					<div class="form-group">
					<label>Select Job Functions</label>
					<?php $job_function_list = $jobwallobj->get_job_functions(); ?>
					<select name="job_function_area" class="select_multi form-control" data-live-search="true" data-live-search-placeholder="Job Functions">
					    <option value="">Select Job Functions</option>
					    <?php
						if(!empty($job_function_list))
						{
						    foreach($job_function_list as $job_functions)
						    {
							?>
							<option <?php echo ($jobwallobj->search_job_function == $job_functions['id']) ? 'selected="selected"' : '' ?> value="<?php echo $job_functions['id']; ?>"><?php echo $job_functions['job_function_name']; ?></option>
							<?php
						    }
						}
					    ?>
					</select>
					</div>
				    </div>
				    <div class="col-sm-6">
					<div class="form-group">
					    <?php $job_skills_list = $jobwallobj->get_job_skills(); ?>
					    <label>Key skills (keywords related to job) <em>*</em></label>
					    <select class="select_multi form-control" multiple name="key_skills[]" id="key_skills" data-live-search="true" placeholder="Key Skills" data-live-search-placeholder="Select key skills">
						<?php
						    if(!empty($job_skills_list))
						    {
							$key_skills = array();
							if(isset($jobwallobj->key_skills))
							{
							    $key_skills = explode(',',$jobwallobj->key_skills);
							}
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
				    <div class="col-md-6">
					<div class="form-group">
					    <label>Location </label>
					    <select name="location[]" id="location_search" multiple class="select_multi form-control" placeholder="Select city" data-live-search="true">
						<option value="">Select city</option>
						<?php
						    if(isset($jobwallobj->location) && !empty($jobwallobj->location))
						    {
							$location_array = explode(",",$jobwallobj->location);
							foreach($location_array as $location)
							{
							    $city = $jobwallobj->get_city_name($location);
							    ?><option selected="selected" value="<?php echo $city['id']; ?>"><?php echo $city['name']; ?></option><?php
							}
						    }
						?>
					    </select>
					</div>
				    </div>
				    <div class="col-md-6">
					<div class="form-group">
					    <label>Salary</label>
					    <div class="input-group">
						<select name="min_salary" class="form-control" onchange="selectionClicked(this,'max_salary')">
						    <?php
						    for($i=0;$i<=14;$i++)
						    {
							?><option <?php echo $jobwallobj->min_salary == $i ? 'selected="selected"' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; echo $i!=0 ? ' Lac' : ''; ?></option><?php
						    }?>
						</select>
						
						<div class="input-group-prepend"><span class="input-group-text">To</span></div>
						<select name="max_salary" class="form-control">
						    <?php
						    for($i=0;$i<=15;$i++)
						    {
							?><option <?php echo $jobwallobj->max_salary == $i ? 'selected="selected"' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; echo $i!=0 ? ' Lac' : ''; ?></option><?php
						    }?>
						</select>
					    </div>
					</div>
				    </div>
				    <div class="col-md-6">
					<div class="form-group">
					    <label>Experience</label>
					    <div class="input-group">
						<select name="min_exp" class="form-control" onchange="selectionClicked(this,'max_exp')">
						    <?php
						    for($i=0;$i<=14;$i++)
						    {
							?><option <?php echo $jobwallobj->min_exp == $i ? 'selected="selected"' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; echo $i!=0 ? ' Years' : ''; ?></option><?php
						    }?>
						</select>
						
						<div class="input-group-prepend"><span class="input-group-text">To</span></div>
						<select name="max_exp" class="form-control">
						    <?php
						    for($i=0;$i<=15;$i++)
						    {
							?><option <?php echo $jobwallobj->max_exp == $i ? 'selected="selected"' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; echo $i!=0 ? ' Years' : ''; ?></option><?php
						    }?>
						</select>
					    </div>
					</div>
				    </div>
				    <div class="col-md-12">
					<div style="margin-top: 0px;" class="submitbutton profile_button">
					    <input class="register btn btn-success" type="submit" name="search_result" value="Search Result" />
					    <input style="margin-left: 15px;" class="register btn btn-info" type="submit" name="reset_search" value="See All Result" />
					</div>
				    </div>
				</div>
			    </form>
			</div>
			<div class="jobwallmain">
                            <?php
                            $from = ($page-1)*$limit;
                            $to = (($page-1)*$limit) + $limit;
                            if($to > $jobwallobj->total_records)
                                $to = $jobwallobj->total_records;
                            $total = $jobwallobj->total_records;
                            if(!empty($jobwall))
                            {
				?>
				<div class="row">
				    <div class="col-sm-12">
					<div class="pagination_main pull-right">
					    <ul class="pagination">
					    <?php
					    $current_page = 1;
					    if(isset($_GET['page']) && $_GET['page']!='')
					    {
						$current_page = $_GET['page'];
					    }
					    for ($page=1; $page <= $total_pages ; $page++):?>
						<?php if($current_page == $page)
						{
						    ?><li class="page-item active"><a class="page-link" href="#"><?php  echo $page; ?></a></li><?php
						}
						else
						{?>
						    <li class="page-item"><a href='<?php echo "?page=$page"; ?>' class="page-link"><?php  echo $page; ?></a></li>
						    <?php
						}?>
					    <?php endfor; ?>
					    </ul>
					</div>
					<div class="pull-right" style="padding-top: 8px;padding-right: 20px;font-size: 15px;color: #6e6e6e;"><?php echo 'Showing '.$from.' to '.$to.' Out of '.$total; ?></div>
				    </div>
				</div>
				<?php 
                                foreach($jobwall as $joblist)
                                {
                                    ?>
                                    <div class="jabwallbox">
                                        <div class="jobtitle"><h4><?php echo $joblist['job_title']; ?></h4></div>
                                        <div class="jabdetail">
                                            <div class="row">
						<?php /* ?><div class="col-md-12"><p><strong>Industry Type : </strong> <?php echo $joblist['industry_type']; ?></p></div><?php */ ?>
						<div class="col-md-12"><p><strong>Job functions area : </strong> <?php echo $joblist['job_function_area']; ?></p></div>
                                                <div class="col-md-6">
                                                    <p><strong>Location : </strong> <?php echo $joblist['job_location']; ?></p>
                                                    <p><strong>Experience : </strong> <?php echo $joblist['min_exp']; ?> - <?php echo $joblist['max_exp']; ?> years</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Salary range : </strong> <?php echo number_format($joblist['min_salary']*100000); ?> to <?php echo number_format($joblist['max_salary']*100000); ?> annually</p>
						    <p><strong>Education : </strong> <?php echo $joblist['education']; ?></p>
                                                </div>
                                            </div>
                                            
                                            <p><strong>Skills : </strong> <?php echo $joblist['job_skills']; ?></p>
                                            
					    <?php
					    if(isset($_SESSION['uid']) && $_SESSION['uid'] != ''){ ?>
						<?php if($_SESSION['userdata']['member_type'] == 'Paid' && $_SESSION['userdata']['membership_expire_date'] >= date('Y-m-d h:i:s'))
						{?>
						<div class="contactinfobox">
						    <h5>Contact Information</h5>
						    <div class="row">
							<div class="col-md-6">
							    <p><strong>Company or Organization : </strong> <?php echo $joblist['company_name']; ?></p>
							</div>
							<div class="col-md-6">
							    <p><strong>Mail : </strong> <?php echo $joblist['contact_email']; ?></p>
							</div>
							<div class="col-md-6">
							    <p><strong>Contact No. : </strong> <?php echo $joblist['mobile_no']; ?></p>
							</div>
						    </div>
						</div>
						<?php
						}
						else
						{
						    ?>
						    <div class="contactinfobox">
							<div class="purchase_button"><a href="#">Purchase Membership to get contact details</a></div>
							<h5>Contact Information</h5>
							<div class="row blur">
							    <div class="col-md-12">
								<p><strong>Company or Organization : </strong> XXXXXXXXXXXXXXX-XXXXX-XXXXX</p>
							    </div>
							    <div class="col-md-6">
								<p><strong>Mail : </strong> XXXXXXX@XXXXXXXX<p>
							    </div>
							    <div class="col-md-6">
								<p><strong>Contact No. : </strong> XXXXXXXXXXX</p>
							    </div>
							</div>
						    </div>
						    <?php
						}?>
					    <?php
					    }
					    else
					    {
						?>
						<div class="contactinfobox">
						    <div class="logintosee"><a href="login">Login</a> to see contact information</div>
						</div>
						<?php
					    }?>
                                            <?php /* ?>
                                            <div class="jobfooter">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        This job is usefull? <a class="btn btn-primary btnusefull" href="#">Yes</a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="alignright">
                                                            <a href="#" class="btn btn-outline-success"><i class="fa fa-heart-o" aria-hidden="true"></i> Save this Job</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php */ ?>
                                        </div>
                                    </div>
                                    <?php
                                }
				?>
				<div class="row">
				    <div class="col-sm-12">
					<div class="pagination_main pull-right">
					    <ul class="pagination">
					    <?php
					    $current_page = 1;
					    if(isset($_GET['page']) && $_GET['page']!='')
					    {
						$current_page = $_GET['page'];
					    }
					    for ($page=1; $page <= $total_pages ; $page++):?>
						<?php if($current_page == $page)
						{
						    ?><li class="page-item active"><a class="page-link" href="#"><?php  echo $page; ?></a></li><?php
						}
						else
						{?>
						    <li class="page-item"><a href='<?php echo "?page=$page"; ?>' class="page-link"><?php  echo $page; ?></a></li>
						    <?php
						}?>
					    <?php endfor; ?>
					    </ul>
					</div>
					<div class="pull-right" style="padding-top: 8px;padding-right: 20px;font-size: 15px;color: #6e6e6e;"><?php echo 'Showing '.$from.' to '.$to.' Out of '.$total; ?></div>
				    </div>
				</div>
				<?php
                            }
			    else
			    {
				?><div class="jabwallbox"><div class="textcenter" style="font-size: 15px;color: #6e6e6e;">No record found with this search criteria</div></div><?php
			    }
                            ?>
			    
                        </div>
                    </div>
                    <div class="rightbar">
			<div class="profilepart">
			    <div class="candidatedetails">
				<h3 style="margin-top: 0px;">Service Partners</h3>
			    </div>
			    <div class="servicepartner">
				<ul>
				    <li><a href="#"><img src="assets/images/careernxg.png" alt="careernxg" /></a></li>
				</ul>
			    </div>
			</div>
		    </div>
                </div>
       </div>
    </div>
</section>