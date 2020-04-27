<?php

namespace Core;

final class App{

    private $container;

    static $instance = null;

    private function __construct(){}

    private function __wakeup(){}

    private function __clone(){}

    public static function getInstance()
    {
        if(static::$instance)
            self::$instance = new static;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function set($name, $value)
    {
        $this->container[$name] = $value;
    }

    public function get($name)
    {
        return $this->container[$name];
    }
}