<?php

use Core\Config;
use DB\SQL;
use DB\SQL\Schema;
use NilPortugues\Sql\QueryBuilder\Builder\MySqlBuilder;

return [
    'db' => function(){
        $db = Config::get('app.db', 'app/config');
        $dbh = new PDO(
            "mysql:dbname={$db['name']};host={$db['host']}",
            $db['user'],
            $db['password']
        );
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    },
    'schema' => function(){
        $db = Config::get('app.db', 'app/config');
        return new Schema(
            new SQL(
                "mysql:dbname={$db['name']};host={$db['host']}",
                $db['user'],
                $db['password']
            )
        );
    },
    'builder' => function(){
        return new MySqlBuilder();
    }
];