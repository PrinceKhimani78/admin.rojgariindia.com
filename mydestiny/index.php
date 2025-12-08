<?php
@session_start();
include('controller/application.php');
include('../plugins/mailer/index.php');
define('SITE_URL','https://www.foursis.com/');
$grecaptcha_key = '6Le7gFoUAAAAAE8R-zgwbhzp2d40oOA22Qq2qJJy';
$grecaptcha_secret = '6Le7gFoUAAAAAJOhjIdEyHaqrli_CTwB7TCHDqRV';
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
$app = new Application();
if($app->checklogin() === false)
{
    if(isset($_GET['view']) && $_GET['view'] != "login")
    {
        ?><script>window.location='?view=login'</script><?php
        exit;
    }
}
function setcontroller($controller_name)
{
    include('controller/'.$controller_name . '.php');
    return $setcontrollerfile = ucfirst($controller_name);
}
if($app->checklogin() === true)
{
    include('view/header.php');
}
include('view/' . $file);

if($app->checklogin() === true)
{
    include('view/footer.php');
}
?>