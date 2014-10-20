<?php
namespace Buytogether\Library;

use Exception;

class ErrorLibrary
{
    private static $exceptions = array();

    public static function setException(Exception $e)
    {
        self::$exceptions[] = $e;
    }
    public static function getExceptions()
    {
        return self::$exceptions;
    }
    public static function isException()
    {
        return !empty(self::$exceptions);
    }
    public static function getAllExceptionMsg()
    {
        $msg = '';

        foreach (self::getExceptions() as $e) {
            $msg .= $e->getMessage().'!';
        }

        return $msg;
    }
}
