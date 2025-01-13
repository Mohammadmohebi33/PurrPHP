<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\controllers\AboutController;
use App\controllers\SiteController;
use App\core\Application;

$app  = new Application(dirname(__DIR__));


$app->router->get('/', [SiteController::class, 'home']);


$app->run();

