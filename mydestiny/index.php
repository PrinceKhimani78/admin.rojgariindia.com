<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

@session_start();

define('BASE_PATH', __DIR__);

include BASE_PATH . '/controller/application.php';
include BASE_PATH . '/../plugins/mailer/index.php';

define('SITE_URL','https://admin.rojgariindia.com/mydestiny/');

$grecaptcha_key = '6Le7gFoUAAAAAE8R-zgwbhzp2d40oOA22Qq2qJJy';
$grecaptcha_secret = '6Le7gFoUAAAAAJOhjIdEyHaqrli_CTwB7TCHDqRV';

if (isset($_GET['view']) && $_GET['view'] != "home" && $_GET['view'] != "") {

    $file = $_GET['view'] . '.php';
    $page = $_GET['view'];

    if (!file_exists(BASE_PATH . '/view/' . $file)) {
        $file = 'index.php';
        $page = 'home';
    }

    if (file_exists(BASE_PATH . '/controller/' . $file)) {
        include BASE_PATH . '/controller/' . $file;
        $controllerfile = ucfirst($_GET['view']);
        $controller = new $controllerfile;
    }

} else {
    $file = 'index.php';
    $page = 'home';
}

$app = new Application();

if ($app->checklogin() === false) {
    if (isset($_GET['view']) && $_GET['view'] != "login") {
        echo "<script>window.location='?view=login'</script>";
        exit;
    }
}

function setcontroller($controller_name)
{
    include BASE_PATH . '/controller/' . $controller_name . '.php';
    return ucfirst($controller_name);
}

if ($app->checklogin() === true) {
    include BASE_PATH . '/view/header.php';
}

include BASE_PATH . '/view/' . $file;

if ($app->checklogin() === true) {
    include BASE_PATH . '/view/footer.php';
}
?>
