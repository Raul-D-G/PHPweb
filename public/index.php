<?php

require_once __DIR__.'/../../vendor/autoload.php';


use app\core\Application;
use app\controllers\SController;


$app = new Application(dirname(__DIR__));

$app->router->get('/', [SController::class, 'files']);
$app->router->get('/search', [SController::class, 'search']);
$app->router->post('/search', [SController::class, 'handleSearch']);

$app->run();
