<?php

use Buki\Router;
use Core\Config;

require_once "vendor\autoload.php";

if( ! defined('APP_PATH'))
    define('APP_PATH', __DIR__ . '/app');

if( ! defined('CONFIG_PATH'))
    define('CONFIG_PATH', APP_PATH . '/config');

$router = new Router(Config::get('app.router', CONFIG_PATH));

require_once APP_PATH . '/config/routes.php';

$router->run();
