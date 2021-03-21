<?php
require_once(__DIR__ . '/vendor/autoload.php');

use Illuminate\Database\Capsule\Manager as Capsule;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => env('MYSQL_DB_HOST'),
    'database' => env('MYSQL_DB_DATABASE'),
    'username' => env('MYSQL_DB_USER_NAME'),
    'password' => env('MYSQL_DB_PASSWORD'),
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
