<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
function send_mail($to,$subject,$mailbody)
{
    $issmtp = 0;
    if($issmtp == 1)
    {
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.sendgrid.net';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'prashant3391';                 // SMTP username
            $mail->Password = '$PraJani3391$';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            $from = 'foursis01@gmail.com';
            $sitetitle = 'Foursis.com';
            $replyto = 'noreply@foursis.com';
            //Recipients
            $mail->setFrom($from, $sitetitle);
            //$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
            $mail->addAddress($to);               // Name is optional
            $mail->addReplyTo($replyto, $sitetitle);
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
        
            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $mailbody;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
    else
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <info@foursis.com>' . "\r\n";
        $headers .= "Reply-To: <noreply@foursis.com>\r\n";
        mail($to,$subject,$mailbody,$headers);
    }
}
?>