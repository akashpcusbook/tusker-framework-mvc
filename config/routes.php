<?php

use Tusker\App\Controller\Welcome;
use Tusker\Framework\Router\Route;

Route::web(Route::HTTP_GET, '/', Welcome::class, 'index', 'welcome');