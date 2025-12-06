<?php
class Login extends Application
{
    function __construct()
    {
        parent::__construct();
        if(isset($_GET['action']) && $_GET['action'] == 'logout')
        {
            session_destroy();
            ?><script type="text/javascript">window.location="login"</script><?php
            exit;
        }
        if($this->checklogin() === true)
        {
            ?><script>window.location='account'</script><?php
            exit;
        }
        if(isset($_POST['login']) && $_POST['login'] == 'Login')
        {
            $this->dologin($_POST);
        }
        if(isset($_POST['forgot_password']) && $_POST['forgot_password'] == 'Recover')
        {
            $this->forgot_pass($_POST);
        }
        if(isset($_POST['change_password']) && $_POST['change_password'] == 'Change Password')
        {
            $this->change_password($_GET['account_id'],$_GET['resetkey'],$_POST);
        }
        if(isset($_POST['resend_link']) && $_POST['resend_link'] == 'Submit')
        {
            $this->resend_activation_link($_POST);
        }
    }
    function dologin($posted_data)
    {
        if($this->validarecaptcha($posted_data['g-recaptcha-response']))
        {
            if(isset($posted_data['email']) && $posted_data['email'] != '' && isset($posted_data['password']) && $posted_data['password'] != '')
            {
                $selquery = 'select * from candidates where email = "'.$posted_data['email'].'" and password = "'.$this->encode_pass($posted_data['password']).'"';
                echo $selquery;
                
                $userdata = $this->selectcustom($selquery,$find='first');
                if(!empty($userdata))
                {
                    if($posted_data['email'] == $userdata['email'])
                    {
                        $logincheck = false;
                        if(md5($posted_data['password']) == $userdata['password_md5'])
                        {
                            $logincheck = true;
                            $this->update(array('password','password_md5'),array($this->encode_pass($posted_data['password']),''),'candidates',array('id' => $userdata['id'], 'email' => $userdata['email']));
                        }
                        elseif($this->encode_pass($posted_data['password']) == $userdata['password'])
                        {
                            $logincheck = true;
                        }
                        if($userdata['email_verified'] != 'Yes')
                        {
                            $logincheck = false;
                            $_SESSION['message'] = '<div class="alert alert-danger">Your account email is not verified. Please verify your email to login. <a href="resend-link">Click here</a> to resend activation link fo verify your email account.</div>';
                        }
                        if($userdata['status'] != 'Active')
                        {
                            $logincheck = false;
                            $_SESSION['message'] = '<div class="alert alert-danger">Your account is deactivated. Please contact administrator.</div>';
                        }
                        if($logincheck === true)
                        {
                            $_SESSION['uid'] = $userdata['id'];
                            $_SESSION['userdata'] = $userdata;
                            /*$_SESSION['fullname'] = $userdata['fullname'];
                            $_SESSION['email'] = $userdata['email'];*/
                            unset($_SESSION['is_search']);
                            ?><script type="text/javascript">window.location="account"</script><?php
                            exit;
                        }
                        else
                        {
                            ?><script type="text/javascript">window.location="login"</script><?php
                            exit;
                        }
                    }
                }
                else
                {
                    $_SESSION['message'] = '<div class="alert alert-danger">Invalid Email or Password</div>';
                    ?><script type="text/javascript">window.location="login"</script><?php
                    exit;
                }
            }
        }
        else
        {
            $_SESSION['message'] = '<div class="alert alert-danger">Robot verification Expired.</div>';
            ?><script type="text/javascript">window.location="login"</script><?php
            exit;
        }
    }
    function forgot_pass($posted_data)
    {
        if($this->validarecaptcha($posted_data['g-recaptcha-response']))
        {
            if($posted_data['email'] != '')
            {
                $getuseremail = $this->select(array('id','email','fullname'),'candidates',array('email' => $posted_data['email']),$find='first');
                if(!empty($getuseremail))
                {
                    $resetkey = $this->generateRandomString();
                    $forgot_password_code_expire = date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s'). ' + 1 days'));
                    $reset_link = SITE_URL.'forgot-password?account_id='.$getuseremail['id'].'&resetkey='.$resetkey;
                    $this->update(array('forgot_password_code','forgot_password_code_expire'),array($resetkey,$forgot_password_code_expire),'candidates',array('id' => $getuseremail['id'], 'email' => $getuseremail['email']));
                    $to = $getuseremail['email'];
                    $subject = 'Forgot Password Request : Foursis.com';
                    $message = 'Hi '.$getuseremail['fullname'].',<br /><br />
                    You have requested password reset request. Please click following link to reset your password.<br /><br />
                    Password reset link : '.$reset_link.'<br /><br />
                    Please note that password reset link is valid for 24 hours only.<br /><br />
                    Thanks<br />
                    Foursis Team';
                    send_mail($to,$subject,$message);
                    $_SESSION['message'] = '<div class="alert alert-success">Password reset link sent to your email.</div>';
                    ?><script type="text/javascript">window.location="forgot-password"</script><?php
                    exit;
                }
                else
                {
                    $_SESSION['message'] = '<div class="alert alert-danger">Account with this email does not exist.</div>';
                    ?><script type="text/javascript">window.location="forgot-password"</script><?php
                    exit;
                }
            }
        }
        else
        {
            $_SESSION['message'] = '<div class="alert alert-danger">Robot verification Expired.</div>';
            ?><script type="text/javascript">window.location="forgot-password"</script><?php
            exit;
        }
    }
    function get_forgot_code_key($account_id,$forgot_key)
    {
        return $data = $this->select(array('id'),'candidates',array('id' => $account_id,'forgot_password_code' => $forgot_key,'forgot_password_code_expire >' => date('Y-m-d h:i:s')),$find='first');
    }
    function change_password($account_id,$resetcode,$posted_data)
    {
        if($this->validarecaptcha($posted_data['g-recaptcha-response']))
        {
            $this->update(array('password'),array($this->encode_pass($posted_data['password'])),'candidates',array('id' => $account_id, 'forgot_password_code' => $resetcode,'forgot_password_code_expire >' => date('Y-m-d h:i:s')));
            $_SESSION['message'] = '<div class="alert alert-success">Your password has been changed successfully.</div>';
            ?><script type="text/javascript">window.location="login"</script><?php
            exit;
        }
        else
        {
            $_SESSION['message'] = '<div class="alert alert-danger">Robot verification Expired.</div>';
            ?><script type="text/javascript">window.location="forgot-password"</script><?php
            exit;
        }
    }
    function resend_activation_link($posted_data)
    {
        if($this->validarecaptcha($posted_data['g-recaptcha-response']))
        {
            $get_user = $this->select(array('id','fullname','email_verfiy_code','email_verified','email_code_expire'),'candidates',array('email' => $posted_data['email']),$find='first');
            if(!empty($get_user))
            {
                if($get_user['email_verified'] == 'Yes')
                {
                    $_SESSION['message'] = '<div class="alert alert-success">Your email is already verified.</div>';
                    ?><script type="text/javascript">window.location="login"</script><?php
                    exit;
                }
                else
                {
                    if($get_user['email_code_expire'] < date('Y-m-d h:i:s'))
                    {
                        $email_verify_code = $this->generateRandomString();
                        $activation_link = SITE_URL.'register?account_id='.$get_user['id'].'&activationkey='.$email_verify_code;
                        $email_code_expire = date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s'). ' + 1 days'));
                        $this->update(array('email_verfiy_code','email_code_expire'),array($email_verify_code,$email_code_expire),'candidates',array('email' => $posted_data['email']));
                        
                    }
                    else
                    {
                        $activation_link = SITE_URL.'register?account_id='.$get_user['id'].'&activationkey='.$get_user['email_verfiy_code'];
                    }
                    $to = $posted_data['email'];
                    $subject = 'Account activation link: Foursis.com';
                    $message = 'Hi '.$get_user['fullname'].',<br /><br />
                    You have requested Account activation link. Please click following link to reset your password.<br /><br />
                    Account activation link : '.$activation_link.'<br /><br />
                    Please note that password reset link is valid for 24 hours only.<br /><br />
                    Thanks<br />
                    Foursis Team';
                    send_mail($to,$subject,$message);
                    $_SESSION['message'] = '<div class="alert alert-success">Activation link is sent to your email.</div>';
                    ?><script type="text/javascript">window.location="resend-link"</script><?php
                    exit;
                }
            }
            else
            {
                $_SESSION['message'] = '<div class="alert alert-danger">No account associated with this email.</div>';
                ?><script type="text/javascript">window.location="resend-link"</script><?php
                exit;
            }
        }
        else
        {
            $_SESSION['message'] = '<div class="alert alert-danger">Robot verification Expired.</div>';
            ?><script type="text/javascript">window.location="forgot-password"</script><?php
            exit;
        }
    }
    function generateRandomString($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
?>