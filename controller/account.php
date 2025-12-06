<?php
include('plugins/image_resize/resize-class.php');
class Account extends Application
{
    function __construct()
    {
        parent::__construct();
        if($this->checklogin() === false)
        {
            ?><script>window.location='login'</script><?php
            exit;
        }
        if(isset($_POST['update_profile']) && $_POST['update_profile'] == 'Update profile')
        {
            $this->update_profile($_POST);
        }
        if(isset($_POST['change_password']) && $_POST['change_password'] == 'Change password')
        {
            $this->change_password($_POST);
        }
    }
    function update_profile($posted_data)
    {
        if($this->validarecaptcha($posted_data['g-recaptcha-response']))
        {
            if(isset($posted_data['gender']) && isset($posted_data['fullname']) && isset($posted_data['phone_no']) && isset($posted_data['country']) && isset($posted_data['state']) && isset($posted_data['location']) && isset($posted_data['experience_year'])  && isset($posted_data['experience_month']) && isset($posted_data['education']) && isset($posted_data['job_function_area']) && isset($posted_data['key_skills']) && isset($posted_data['salary']))
            {
                $job_functions = explode("|",$posted_data['job_function_area']);
                $image_upload = true;
                $resume_upload = false;
                $experience_total = $posted_data['experience_year'].','.$posted_data['experience_month'];
                foreach($posted_data['key_skills'] as $key_skills)
                {
                    $get_values = explode("|",$key_skills);
                    $key_skills_id_array[] = $get_values[0];
                    $key_skills_name_array[] = $get_values[1];
                }
                $key_skills_id = implode(',',$key_skills_id_array);
                $key_skills_name = implode('_',$key_skills_name_array);
                if($_SESSION['userdata']['resume'] == '' && $_FILES['resume']['name'] == '')
                {
                    $_SESSION['message'] = '<div class="alert alert-danger">Please upload the resume.</div>';
                    ?><script type="text/javascript">window.location="profile"</script><?php
                    exit;
                }
                else 
                {
                    $resume = '';
                    $profile_photo = '';
                    if($_FILES['resume']['name'] != '')
                    {
                        $target_dir = "uploads/resume/";
                        $FileType = end(explode('.', strtolower($_FILES['resume']['name'])));
                        $allowedFileType = array('doc','docx','pdf',"jpeg",'jpg','png','txt');
                        $job_functions_name = str_replace(' ','',$job_functions[1]);
                        $job_functions_name = str_replace('/','-',$job_functions_name);
                        $resume_name = $posted_data['gender'].'_'.$job_functions_name.'_'.$key_skills_name.'_'.str_replace(' ','_',$posted_data['fullname']).'_'.date('F').'_'.date('Y').'.'.$FileType;
                        $file_size = $_FILES['resume']['size'];
                        $allowed_size = 2000000;
                        if($this->check_uploaded_file($FileType,$allowedFileType,$file_size,$allowed_size) == true)
                            $resume_upload = true;
                    }
                }
                
                if($_FILES['profile_photo']['name'] != '')
                {
                    $target_dir_img = "uploads/profile_photo/";
                    $FileType = end(explode('.', strtolower($_FILES['profile_photo']['name'])));
                    $allowedFileType = array("gif", "jpeg", "jpg", "png");
                    $image_name = $_SESSION['uid'].'_'.str_replace(' ','_',$posted_data['fullname']).'_'.date('Ymdhis').'.'.$FileType;
                    $file_size = $_FILES['profile_photo']['size'];
                    $allowed_size = 2000000;
                    if($this->check_uploaded_file($FileType,$allowedFileType,$file_size,$allowed_size) == true)
                        $image_upload = true;
                    else
                        $image_upload = false;
                }
                
                if($resume_upload == true)
                {
                    if($_SESSION['userdata']['resume'] != '' && file_exists($_SESSION['userdata']['resume']))
                    {
                        unlink($_SESSION['userdata']['resume']);
                    }
                    $file_temp_name = $_FILES['resume']['tmp_name'];
                    $this->upload_files($target_dir,$resume_name,$file_temp_name);
                    $resume = $target_dir.$resume_name;
                }
                if($image_upload == true)
                {
                    if(isset($target_dir_img))
                    {
                        if($_SESSION['userdata']['profile_photo'] != '' && file_exists($_SESSION['userdata']['profile_photo']))
                        {
                            unlink($_SESSION['userdata']['profile_photo']);
                        }
                        $file_temp_name_img = $_FILES['profile_photo']['tmp_name'];
                        $this->upload_files($target_dir_img,$image_name,$file_temp_name_img);
                        $target_path=$target_dir_img.$image_name;
                        $resizeObj = new resize($target_path);
                        $resizeObj -> resizeImage(200, 200, 'crop');
                        $resizeObj -> saveImage($target_path, 100);
                        $profile_photo = $target_dir_img.$image_name;
                    }
                }
                $fields_array = array('gender','fullname','experience_year_month','education','job_function_area','key_skills','salary','phone_no','country','state','location');
                $data_array = array($posted_data['gender'],addslashes($posted_data['fullname']),$experience_total,addslashes($posted_data['education']),$job_functions[0],$key_skills_id,$posted_data['salary'],$posted_data['phone_no'],$posted_data['country'],$posted_data['state'],$posted_data['location']);
                if($resume !='')
                {
                    array_push($fields_array,'resume');
                    array_push($data_array,$resume);
                }
                if($profile_photo != '')
                {
                    array_push($fields_array,'profile_photo');
                    array_push($data_array,$profile_photo);
                }
                $this->update($fields_array,$data_array,'candidates',array('id' => $_SESSION['uid']));
                $userdata = $this->select(array('all'),'candidates',array('id' => $_SESSION['uid']),$find='first');
                $_SESSION['userdata'] = $userdata;
                $_SESSION['message'] = '<div class="alert alert-success">Profile updated has been updated.</div>';
                ?><script type="text/javascript">window.location="profile"</script><?php
                exit;
            }
            else
            {
                $_SESSION['message'] = '<div class="alert alert-danger">Please fillup all the information.</div>';
                ?><script type="text/javascript">window.location="profile"</script><?php
                exit;
            }
        }
        else
        {
            $_SESSION['message'] = '<div class="alert alert-danger">Robot verification Expired.</div>';
            ?><script type="text/javascript">window.location="profile"</script><?php
            exit;
        }
    }
    function get_profile_photo()
    {
        if($_SESSION['userdata']['profile_photo'] != '' && file_exists($_SESSION['userdata']['profile_photo']))
            return $_SESSION['userdata']['profile_photo'];
        else
            return 'assets/images/dummy.png';
    }
    function check_uploaded_file($FileType,$allowedFileType,$file_size,$allowed_size)
    {
        if(in_array($FileType, $allowedFileType))
        {
            if($file_size > $allowed_size)
            {
                $_SESSION["message"]='<div class="alert alert-danger">File is too large. You can not upload more then 2MB.</div>';
                ?><script type="text/javascript">window.location="profile"</script><?php
                exit;
            }
            else
            {
                return true;
            }
        }
        else
        {
            $_SESSION["message"]='<div class="alert alert-danger">Invalid File format.</div>';
            ?><script type="text/javascript">window.location="profile"</script><?php
            exit;
        }
    }
    function upload_files($target_dir,$file_name,$file_temp_name)
    {
        if (!move_uploaded_file($file_temp_name,$target_dir.$file_name)) 
        {
            $_SESSION["message"]='<div class="alert alert-danger">Error:-  The File Could Not Be uploaded, Try Again</div>';
            ?><script type="text/javascript">window.location="profile"</script><?php
            exit;
        }
    }
    function change_password($posted_data)
    {
        if($this->validarecaptcha($posted_data['g-recaptcha-response']))
        {
            if(isset($_POST['old_password']) && $_POST['old_password'] != '' && isset($_POST['password']) && $_POST['password'] != '' && isset($_POST['cpassword']) && $_POST['cpassword'] != '')
            {
                if($_POST['password'] == $_POST['cpassword'])
                {
                    $newpassword = $this->encode_pass($posted_data['password']);
                    $old_password = $this->encode_pass($posted_data['old_password']);
                    $effected_row = $this->update(array('password'),array($newpassword),'candidates',array('password' => $old_password,'id' => $_SESSION['uid']));
                    if($effected_row > 0)
                    {
                        $_SESSION['message'] = '<div class="alert alert-success">Password changed sucessfully.</div>';
                        ?><script type="text/javascript">window.location="change-password"</script><?php
                        exit;
                    }
                    else
                    {
                        if($posted_data['password'] == $posted_data['old_password'])
                        {
                            $_SESSION['message'] = '<div class="alert alert-success">Password changed sucessfully.</div>';
                        }
                        else
                        {
                            $_SESSION['message'] = '<div class="alert alert-danger">Current password is incorrect.</div>';
                        }
                        ?><script type="text/javascript">window.location="change-password"</script><?php
                        exit;
                    }
                }
                else
                {
                    $_SESSION['message'] = '<div class="alert alert-danger">New password and confirm password did not match.</div>';
                    ?><script type="text/javascript">window.location="change-password"</script><?php
                    exit;
                }
            }
            else
            {
                $_SESSION['message'] = '<div class="alert alert-danger">Please fillup all the password detail.</div>';
                ?><script type="text/javascript">window.location="change-password"</script><?php
                exit;
            }
        }
        else
        {
            $_SESSION['message'] = '<div class="alert alert-danger">Robot verification Expired.</div>';
            ?><script type="text/javascript">window.location="change-password"</script><?php
            exit;
        }
    }
}
?>