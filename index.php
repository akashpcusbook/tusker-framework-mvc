<?php

use Tusker\App\App;
use Tusker\App\Kernel;

require_once './vendor/autoload.php';

ini_set("log_errors", 1);
ini_set("error_log", app_path('var/logs/errors.log'));

if ('dev' === env('APP_MODE', 'dev')) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$kernel = new Kernel();

App::load($kernel);
