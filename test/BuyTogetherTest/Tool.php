<?php

namespace BuyTogetherTest;

class Tool
{
    private static $cfg;
    public static function getConfig()
    {
        if (self::$cfg == null) {
            self::$cfg = new \Fruit\Config(dirname(dirname(__DIR__)), 'test');
        }
        return self::$cfg;
    }
}
