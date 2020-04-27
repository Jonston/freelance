<?php

require_once __DIR__ . '/vendor/autoload.php';

use Commands\ParseEmployersCommand;
use Commands\ParseProjectsCommand;
use Commands\ParseSkillsCommand;
use Symfony\Component\Console\Application;

if( ! defined('APP_PATH'))
    define('APP_PATH', __DIR__ . '/app');

if( ! defined('CONFIG_PATH'))
    define('CONFIG_PATH', APP_PATH . '/config');

$app = new Application();
$app->add(new ParseEmployersCommand());
$app->add(new ParseSkillsCommand());
$app->add(new ParseProjectsCommand());
$app->run();