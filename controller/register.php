<?php
class Register extends Application
{
    function __construct()
    {
        parent::__construct();
        if($this->checklogin() === true)
        {
            ?><script>window.location='account'</script><?php
            exit;
        }
        if(isset($_POST['register']) && $_POST['register'] == 'Register')
        {
            $this->registration($_POST);
        }
    }
    function email_verification($account_id,$activationkey)
    {
        $data = $this->select(array('email_verified','email_code_expire'),'candidates',array('id' => $account_id,'email_verfiy_code' => $activationkey),$find='first');
        if(!empty($data))
        {
            if($data['email_verified'] == 'Yes')
            {
                $verification_status = 'active';
            }
            else if($data['email_code_expire'] < date('Y-m-d h:i:s'))
            {
                $verification_status = 'code_expire';
            }
            else
            {
                $this->update(array('email_verified'),array('Yes'),'candidates',array('id' => $account_id,'email_verfiy_code' => $activationkey));
                $verification_status = 'success';
            }
        }
        else
        {
            $verification_status = 'failed';
        }
        return $verification_status;
    }
    function registration($posted_data)
    {
        if(isset($posted_data['gender']) && isset($posted_data['fullname']) && isset($posted_data['email']) && isset($posted_data['password'])  && isset($posted_data['cpassword']) && isset($posted_data['contactnumber']) && isset($posted_data['country']) && isset($posted_data['state']) && isset($posted_data['location']))
        {
            if(trim($posted_data['gender']) != '' && trim($posted_data['fullname']) != '' && trim($posted_data['contactnumber']) != ''  && trim($posted_data['country']) != ''  && trim($posted_data['state']) != ''  && trim($posted_data['location']) != '')
            {
                if(filter_var($posted_data['email'], FILTER_VALIDATE_EMAIL) && $posted_data['password'] == $posted_data['cpassword'])
                {
                    if($this->validarecaptcha($posted_data['g-recaptcha-response']))
                    {
                        $ipaddress = $_SERVER['REMOTE_ADDR'];
                        $refid=0;
                        if(isset($posted_data['refid']) && is_numeric($posted_data['refid']))
                        {
                            $getrefid = $this->select(array('id'),'candidates',array('id' => $posted_data['refid']),$find='first');
                            if(!empty($getrefid))
                            {
                                $refid = $getrefid['id'];
                            }
                        }
                        if(isset($posted_data['job_function_area']) && is_numeric($posted_data['job_function_area']))
                        {
                            $job_function_area = $posted_data['job_function_area'];
                        }
                        $check_email_registered = array();
                        $check_email_registered = $this->select(array('email'),'candidates',array('email' => $posted_data['email']));
                        $email_code_expire = date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s'). ' + 1 days'));
                        if(empty($check_email_registered))
                        {
                            $email_verify_code = $this->generateRandomString();
                            $fields=array('ref_id','fullname','gender','email','password','phone_no','country','state','location','ip_address','job_function_area','email_verfiy_code','email_code_expire');
                            $data = array($refid,addslashes($posted_data['fullname']),$posted_data['gender'],$posted_data['email'],$this->encode_pass($posted_data['password']),$posted_data['contactnumber'],$posted_data['country'],$posted_data['state'],$posted_data['location'],$ipaddress,$posted_data['job_function_area'],$email_verify_code,$email_code_expire);
                            $tablename="candidates";
                            $lastinsertedid = $this->insert($fields,$data,$tablename);
                            /*Email code*/
                            $activation_link = SITE_URL.'register?account_id='.$lastinsertedid.'&activationkey='.$email_verify_code.'';
                            $to = $posted_data['email'];
                            $subject = 'Thank you for creating account with Foursis.';
                            $message = 'Hi '.$posted_data['fullname'].',<br /><br />
                            Thank you for creating account with us.<br />
                            Now please confirm your email by following activation link (Just click on it or copy and paste in your browser).<br /><br />
                            Activation Link : '.$activation_link.'<br /><br />
                            Thanks<br />
                            Foursis Team';
                            send_mail($to,$subject,$message);
                            $_SESSION['message'] = '<div class="alert alert-success">Thank you for register with us. Please check your account and verify your email. Link is valid for 24 hours only.</div>';
                            ?><script type="text/javascript">window.location="register"</script><?php
                            exit;
                            /*Email code*/
                        }
                        else
                        {
                            $_SESSION['message'] = '<div class="alert alert-danger">Account with this email is already exist. Please use another email.</div>';
                            ?><script type="text/javascript">window.location="register"</script><?php
                            exit;
                        }
                    }
                }
            }
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