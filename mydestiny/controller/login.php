<?php
class Login extends Application
{
    function __construct()
    {
        parent::__construct();
        if(isset($_POST['login']) && $_POST['login'] == 'Login')
        {
            $this->dologin($_POST);
        }
    }
    function dologin($posted_data)
    {
        if(isset($posted_data['username']) && isset($posted_data['password']))
        {
            $getuser = $this->select(array('user_name','password'),'admin_settings',array('user_name' => $posted_data['username'],'password' => $this->encode_pass($posted_data['password'])));
            if(!empty($getuser[0]))
            {
                if($getuser[0]['user_name'] == $posted_data['username'] && $getuser[0]['password'] == $this->encode_pass($posted_data['password']))
                {
                    $_SESSION['user'] = $posted_data['username'];
                    ?><script>window.location='?view=dashboard'</script><?php
                    exit;
                }
                else{
                    $_SESSION['message'] = '<div class="alert alert-danger">Incorrect username or password.</div>';
                    ?><script type="text/javascript">window.location="?view=login"</script><?php
                    exit;
                }
            }
            else{
                $_SESSION['message'] = '<div class="alert alert-danger">Incorrect username or password.</div>';
                ?><script type="text/javascript">window.location="?view=login"</script><?php
                exit;
            }
            print_r($getuser);exit;
        }
    }
}
?>