<?php

// if (isset($_SERVER['ENVIRONMENT']) && $_SERVER['ENVIRONMENT'] == 'prod') {
//     return [
//         'class' => 'yii\db\Connection',
//         'dsn' => "mysql:host={$_SERVER['PROD_DB_HOST']};dbname={$_SERVER['PROD_DATABASE']}",
//         'username' => $_SERVER['PROD_DB_USERNAME'],
//         'password' => $_SERVER['PROD_DB_PASSWORD'],
//         'charset' => 'utf8',

//     ];
// }

// return [
//     'class' => 'yii\db\Connection',
//     'dsn' => "mysql:host=mariadb;dbname=delta",
//     'username' => 'root',
//     'password' => 'root',
//     'charset' => 'utf8',

// ];

////////////////////////////////////////

$host = $_SERVER['HTTP_HOST']; // Get the current host

if ($host === 'localhost') {
    // Localhost environment
    return [
        'class' => 'yii\db\Connection',
        'dsn' => "mysql:host=mariadb;dbname=delta",
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
    ];

} elseif ($host === 'delta.doubledeals.co.ke') {
    // Production environment for doubledeals.co.ke
    return [
        'class' => 'yii\db\Connection',
        'dsn' => "mysql:host={$_SERVER['PROD_DB_HOST']};dbname={$_SERVER['PROD_DATABASE']}",
        'username' => $_SERVER['PROD_DB_USERNAME'],
        'password' => $_SERVER['PROD_DB_PASSWORD'],
        'charset' => 'utf8',
    ];

} elseif ($host === 'delta.wuaze.com') {
    // Production environment for wuaze.com
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=sql110.infinityfree.com;dbname=if0_37114096_delta',
        'username' => 'if0_37114096',
        'password' => 'QcIDYuIrKJ',
        'charset' => 'utf8',
    ];

} elseif ($host === 'deltakosmetics.com') {
    // Production environment for deltakosmetics.com
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=deltolxj_deltakosmetics',
        'username' => 'deltolxj_deltakosmetics',
        'password' => 'M70()J%O_8Kk',
        'charset' => 'utf8',
    ];
} else {
    // Default fallback or other environments
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=mariadb;dbname=delta',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
    ];
}
