<?php

use Tusker\App\App;
use Tusker\App\Kernel;

require_once './vendor/autoload.php';

$kernel = new Kernel();

App::load($kernel);
