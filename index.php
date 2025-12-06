<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
@session_start();

date_default_timezone_set('Asia/Calcutta');

include('controller/application.php');

include('plugins/mailer/index.php');

define('SITE_URL','https://www.foursis.com/');

$grecaptcha_key = '6Le7gFoUAAAAAE8R-zgwbhzp2d40oOA22Qq2qJJy';

$grecaptcha_secret = '6Le7gFoUAAAAAJOhjIdEyHaqrli_CTwB7TCHDqRV';

define('ADMIN_EMAIL','foursis01@gmail.com');

/*$from = 'noreply@foursis.com';

$to = 'john.test3391@gmail.com';

$mailsubject = 'Testing SMTP';

$mailmessage = 'Testing Message';

$sitetitle = 'Foursis.com';

send_mail($from,$to,$mailsubject,$mailmessage,$sitetitle);*/



if(isset($_GET['view']) && $_GET['view'] != "home" && $_GET['view'] != "")

{

    $file = $_GET['view'] . '.php';

    $page = $_GET['view'];

    if(!file_exists('view/'.$file))

    {

        $file = 'index.php';

        $page = 'home';

    }

    if(file_exists('controller/'.$file))

    {

        include('controller/'.$file);

        $controllerfile = ucfirst($_GET['view']);

        $controller = new $controllerfile;

    }

}

else{

    $file = 'index.php';

    $page = 'home';

}

//$app = new Application();

function setcontroller($controller_name)

{

    include('controller/'.$controller_name . '.php');

    return $setcontrollerfile = ucfirst($controller_name);

}

include('view/header.php');

include('view/' . $file);

include('view/footer.php')

?>