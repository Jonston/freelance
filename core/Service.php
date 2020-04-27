<?php

namespace Core;

use Pimple\Container;

class Service
{
    static $container = null;

    private function __construct(){}

    public static function get(string $name)
    {
        if(static::$container === null){
            static::$container = new Container();

            $services = Config::get('services', 'app/config');

            foreach($services as $key => $service){
                static::$container[$key] = $service;
            }
        }

        return static::$container[$name];
    }

}