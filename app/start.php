<?php

use Slim\Slim;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Noodlehaus\Config;
use sitename\User\User;

session_cache_limiter(false);
session_start();

ini_set('display_errors', 'On');

define('INC_ROOT', dirname(__DIR__));
require INC_ROOT . '/vendor/autoload.php';

$app = new Slim([
    'mode' => file_get_contents(INC_ROOT . '/mode.php'),
    //use twig
    'view' => new Twig(),
    //set our path to views
    'templates.path' => INC_ROOT . '/app/views'
        ]);

$app->configureMode($app->config('mode'), function() use ($app) {
    $app->config = Config::load(INC_ROOT . "/app/config/{$app->mode}.php");
});


require INC_ROOT . "/app/database.php";
require INC_ROOT . "/app/routes.php";

$app->container->set('user', function () {
    return new User();
});

/**
 *Setup view config
 **/

$view = $app->view();

// if we are in debug mode turn on debuging
$view->parserOptions = [
    'debug' => $app->config->get('twig.debug')
];

//enable twig extensions like if, for ...
$view->parserExtensions = [
    new TwigExtension()
];


