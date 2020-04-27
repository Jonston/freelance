<?php

use Core\Config;
use DB\SQL;
use DB\SQL\Schema;
use Phpmig\Adapter;
use Pimple\Container;

$config = Config::get('app', 'app/config');

$container = new Container();

$container['db'] = function() use ($config){
    $dbh = new PDO(
        "mysql:dbname={$config['db']['name']};host={$config['db']['host']}",
        $config['db']['user'],
        $config['db']['password']
    );
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
};

$container['schema'] = function() use ($config){
    $db = $config['db'];
    return new Schema(
        new SQL(
            "mysql:dbname=${db['name']};host=${$db['host']}",
            $db['user'],
            $db['password']
        )
    );
};

$container['phpmig.adapter'] = function($c){
    return new Adapter\PDO\Sql($c['db'], 'migrations');
};

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'migrations';

return $container;