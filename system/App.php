<?php

namespace App\System;

use Exception;

class App
{
    /**
     * @var array
     */
    protected static array $registry = [];

    /**
     * @param $key
     * @param $value
     */
    public static function bind($key, $value)
    {
        static::$registry[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, static::$registry)) {
            throw new Exception("{$key} does not exists.");
        }

        return static::$registry[$key];
    }
}