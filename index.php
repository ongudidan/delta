<?php
// Display all errors for debugging purposes

// Set the default timezone to Africa/Nairobi
date_default_timezone_set('Africa/Nairobi');
ini_set("memory_limit", "128M");

require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, "yii2.cfg");
$dotenv->safeLoad();
if (isset($_SERVER['ENVIRONMENT']) && $_SERVER['ENVIRONMENT'] == 'dev') {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/web.php';
(new yii\web\Application($config))->run();
