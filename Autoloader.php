<?php

class Autoloader {
    private static $basepath;

    public static function init($basepath) {
        self::$basepath = $basepath;

        spl_autoload_register('Autoloader::loader');
    }

    public static function loader($className) {
        if (strpos($className, 'connected') !== false) {
            $fileName = self::$basepath . '/' . str_replace("\\", "/", str_replace("connected\\", "", $className)) . ".php";

            if (file_exists($fileName)) {
                require_once($fileName);

                if (class_exists($className)) {
                    return true;
                }
            }
        }

        return false;
    }
}

Autoloader::init(dirname(__FILE__));