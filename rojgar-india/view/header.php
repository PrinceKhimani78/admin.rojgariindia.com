<?php
$app = new Application();

if(isset($_GET['view']) && $_GET['view'] != "home" && $_GET['view'] != "")
{
    $pagekey = $_GET['view'];
}
else
{
    $pagekey = 'home';
}
$getpagedetail = $app->getpagedetail($pagekey);

$pagetitle = $getpagedetail['page_title'];
$meta_keywords = $getpagedetail['meta_keywords'];
$meta_description = $getpagedetail['meta_description'];

if($page == 'register'){
    $pagetitle = 'Registration';
}
else if($page == 'company-profile'){
    $pagetitle = 'Company Profile';
}
else if($page == 'team'){
    $pagetitle = 'Team';
}
else if($page == 'contactus'){
    $pagetitle = 'Contact Us';
}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no" name="viewport">
<title><?php echo $pagetitle; ?> | Rojgar Placement</title>
<meta name="description" content="<?php echo $meta_description; ?>">
<meta name="keywords" content="<?php echo $meta_keywords; ?>">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="assets/plugin/select2/css/select2.min.css" />
<link rel="stylesheet" href="assets/css/style.css" />
<script src="assets/js/jquery-3.3.1.min.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-114523749-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-114523749-1');
</script>
</head>
<body>
    <header class="header <?php if($page == 'home'){?> background-scroll <?php } ?> <?php if(isset($_SESSION['uid']) && $_SESSION['uid'] != ''){echo 'innerheader';}?>">
	<div class="topdetails">
	    <div class="container">
		<div class="cdetaiparent float-left">
		    <div class="cdetail">
			<span>Phone No:</span>
			<a href="#">+91 96624 41222</a>
		    </div>
		    <div class="cdetail">
			<span>Email:</span>
			<a href="mailto:careers@foursis.com">careers@foursis.com</a>
		    </div>
		</div>
		<div class="float-right">
		    <div class="sociallinks">
			<a href="#" class="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
			<a href="#" class="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
			<a href="#" class="linkedin"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
		    </div>
		    <?php if(isset($_SESSION['uid']) && $_SESSION['uid'] != '')
		    {
			?>
			<div class="quicklinks">
			    <a class="dropdown-toggle" href="#" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My account</a>
			    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
				<a class="dropdown-item" href="account">My account</a>
				<a class="dropdown-item" href="profile">My Profile</a>
				<a class="dropdown-item" href="change-password">Change Password</a>
				<a class="dropdown-item" href="jobwall">Job Wall</a>
				<a class="dropdown-item" href="login?action=logout">Logout</a>
			    </div>
			</div>
			<?php
		    }
		    else
		    {?>
		    <div class="quicklinks">
			<a href="login">Login</a>
			<a href="register">Signup</a>
		    </div>
		    <?php
		    } ?>
		</div>
		<div class="clearfix"></div>
	    </div>
	</div>
	<div class="topnav">
	    <div class="container">
		<div class="logo float-left"><a href="home"><img src="assets/images/logo.png" alt="Foursis Technical Solutions" /></a></div>
		<div class="float-right">
		    <div class="mtogglebutton">
			<i class="fa fa-bars" aria-hidden="true"></i>
		    </div>
		    <div class="navigation">
			<div class="logo_mobile"><a href="home"><img src="assets/images/logo.png" alt="Foursis Technical Solutions" /></a></div>
			<ul>
			    <li><a href="home">Home</a></li>
			    <li><a href="#">Service Partners</a></li>
			    <li><a class="submenu1" href="#">About Us</a>
				<ul>
				    <li><a href="company-profile">Company Profile</a></li>
				    <li><a href="team">Leadership Team</a></li>
				</ul>
			    </li>
			    <li><a href="contactus">Contact Us</a></li>
			    <li><a href="#">Other services</a></li>
			</ul>
		    </div>
		</div>
		<div class="clearfix"></div>
	    </div>
	</div>
	<div id="header" class="header_slider <?php if($page == 'home'){ echo 'homepage'; }?>">
	    <div class="container">
                <?php if($page == 'home')
                {?>
                    <div class="silde_content">
                        <h1 class="heading"><span>Welcome</span> To Foursis</h1>
                        <h3>Applying to your desired job. Made easy like never before.</h3>
                        
                        <div class="form_search">
                            <div class="row">
                                <div class="field_item">
                                    <div class="field_content">
                                        <div class="field_lable">Keywords</div>
                                        <input type="text" class="form-control customform" placeholder="Enter job keywords" />
                                    </div>
                                </div>
                                <div class="field_item">
                                    <div class="field_content">
                                        <div class="field_lable">Location</div>
                                        <select class="select_multi" data-live-search="true" data-live-search-placeholder="Search location" data-actions-box="true">
                                            <option value="" selected="selected">All location</option>
                                            <option value="accounting-ssistant">Ahmedabad</option>
                                            <option value="accounting-ssistant">Ankleshawer</option>
                                            <option value="accounting-ssistant">Bhavnagar</option>
                                            <option value="accounting-ssistant">Bharuch</option>
                                            <option value="accounting-ssistant">Botad</option>
                                            <option value="arts-design-media">Rajkot</option>
                                            <option value="charity-voluntary">Navsari</option>
                                            <option value="charity-voluntary">Surat</option>
                                            <option value="charity-voluntary">Vadodara</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="field_item">
                                    <div class="field_content">
                                        <div class="field_lable">Experience</div>
                                        <select class="select_multi" data-live-search-placeholder="Experience" data-actions-box="true">
                                            <option>Experience</option>
                                            <option>Fresher</option>
                                            <option>1 year</option>
                                            <option>2 year</option>
                                            <option>3 year</option>
                                            <option>4 year</option>
                                            <option>5 year</option>
                                            <option>6 year</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="field_item_submit">
                                    <div class="field_content">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                else{
                    ?>
			<?php if(!isset($_SESSION['uid']))
			{
			    ?>
				<div class="pagetitle">
				    <h1 class="headtitle"><?php echo $pagetitle; ?></h1>
				</div>
			<?php
			} ?>
                    <?php
                }?>
	    </div>
	</div>
    </header>