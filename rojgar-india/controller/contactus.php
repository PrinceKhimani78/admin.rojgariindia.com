<?php
class Contactus extends Application
{
    function __construct()
    {
        parent::__construct();
        //echo $this->admin_email = ADMIN_EMAIL;
        if(isset($_POST['contact']) && $_POST['contact'] == 'Submit')
        {
            $this->contact_inquiry($_POST);
        }
    }
    function contact_inquiry($posted_data)
    {
        if($this->validarecaptcha($posted_data['g-recaptcha-response']))
        {
            if(isset($posted_data['fullname']) && isset($posted_data['email']) && isset($posted_data['contactno']) && isset($posted_data['subject']) && isset($posted_data['message']))
            {
                if(trim($posted_data['fullname']) != '' && trim($posted_data['email']) != '' && trim($posted_data['contactno']) != ''  && trim($posted_data['subject']) != ''  && trim($posted_data['message']) != '')
                {
                    if(filter_var($posted_data['email'], FILTER_VALIDATE_EMAIL))
                    {
                        $tablename = 'contact_inquiry';
                        $fields = array('fullname','email','phone_no','subject','message');
                        $data = array(addslashes($posted_data['fullname']),$posted_data['email'],$posted_data['contactno'],addslashes($posted_data['subject']),addslashes($posted_data['message']));
                        $lastinsertedid = $this->insert($fields,$data,$tablename);
                        
                        //mail to user;
                        $to = $posted_data['email'];
                        $subject = 'Thank you for Contacting us. Reference No : #'.$lastinsertedid;
                        $message = 'Hi '.$posted_data['fullname'].',<br /><br />
                        Thank you for contacting us.<br />
                        Our team will contact as soon as possible.<br />
                        Thank you for your patience.<br /><br />
                        Thanks<br />
                        Foursis Team';
                        
                        //mail to admin
                        $toadmin = ADMIN_EMAIL;
                        $subject2 = 'Contact inquiry. Reference No : #'.$lastinsertedid;
                        $message2 = 'Hi Team,<br /><br />
                        Submitted Contact details are mentioned below<br /><br />
                        <b>Fullname : </b>'.$posted_data['fullname'].'<br />
                        <b>Email : </b>'.$posted_data['email'].'<br />
                        <b>Contact No : </b>'.$posted_data['contactno'].'<br />
                        <b>Subject : </b>'.$posted_data['subject'].'<br />
                        <b>Message : </b>'.$posted_data['message'].'<br /><br />
                        
                        Thanks';
                        
                        send_mail($to,$subject,$message);
                        send_mail($toadmin,$subject2,$message2);
                        $_SESSION['message'] = '<div class="alert alert-success">Thank you for contacting us. Our team will contact as soon as possible.</div>';
                        ?><script type="text/javascript">window.location="contactus"</script><?php
                        exit;
                    }
                }
            }
        }
        else
        {
            $_SESSION['message'] = '<div class="alert alert-danger">Robot verification Expired.</div>';
            ?><script type="text/javascript">window.location="contactus"</script><?php
            exit;
        }
    }
}
?>