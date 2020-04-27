<?php

namespace Core;

use Symfony\Component\Config\Definition\Exception\Exception;

class Config {

    public static function get(string $config, string $path)
    {
        $config = explode('.', $config);

        $array = self::load(array_shift($config), $path);

        foreach($config as $item){
            if(array_key_exists($item, $array))
                $array = $array[$item];
            else
                return null;
        }

        return $array;
    }

    public static function load(string $config, string $path)
    {
        $file = trim($path, '/') . '/' . $config . '.php';

        if( ! file_exists($file))
            throw new Exception('File not exists: ' . $file);

        return require $file;
    }
}