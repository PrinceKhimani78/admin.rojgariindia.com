<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

date_default_timezone_set('Asia/Calcutta');

/* ===========================
   LOAD CORE FILES SAFELY
=========================== */

require_once __DIR__ . '/controller/application.php';
require_once __DIR__ . '/../rojgar-india/controller/databaseclass.php';

/* ===========================
   CREATE APPLICATION OBJECT
=========================== */

$app = new Application();

/* ===========================
   ROUTE HANDLING
=========================== */

$view = isset($_GET['view']) && $_GET['view'] != '' ? $_GET['view'] : 'home';
$file = $view . '.php';

if (!file_exists(__DIR__ . '/view/' . $file)) {
    $view = 'home';
    $file = 'index.php';
}

/* ===========================
   AUTH REDIRECT LOGIC
=========================== */

if ($view !== 'login' && $view !== 'logout') {
    if ($app->checklogin() === false) {
        header("Location: ?view=login");
        exit;
    }
}

/* ===========================
   LOAD CONTROLLER (IF EXISTS)
=========================== */

$controllerPath = __DIR__ . '/controller/' . $file;
if (file_exists($controllerPath)) {
    include $controllerPath;
    $controllerClass = ucfirst($view);
    if (class_exists($controllerClass)) {
        new $controllerClass();
    }
}

/* ===========================
   LOAD VIEW LAYOUT
=========================== */

require_once __DIR__ . '/view/header.php';
require_once __DIR__ . '/view/' . $file;
require_once __DIR__ . '/view/footer.php';
