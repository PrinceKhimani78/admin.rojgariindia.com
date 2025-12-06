<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
@session_start();

date_default_timezone_set('Asia/Calcutta');

/* ✅ FIX: Convert /login into ?view=login */
if (!isset($_GET['view'])) {
    $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $_GET['view'] = ($uri != '') ? $uri : 'home';
}

include('controller/application.php');
include('plugins/mailer/index.php');

define('SITE_URL','https://www.foursis.com/');

$grecaptcha_key = '6Le7gFoUAAAAAE8R-zgwbhzp2d40oOA22Qq2qJJy';
$grecaptcha_secret = '6Le7gFoUAAAAAJOhjIdEyHaqrli_CTwB7TCHDqRV';

define('ADMIN_EMAIL','foursis01@gmail.com');

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

function setcontroller($controller_name)
{
    include('controller/'.$controller_name . '.php');
    return ucfirst($controller_name);
}

include('view/header.php');
include('view/' . $file);
include('view/footer.php');
?>
