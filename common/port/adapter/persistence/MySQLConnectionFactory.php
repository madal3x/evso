<?php

namespace connected\common\port\adapter\persistence;

class MySQLConnectionFactory {
    private static $instance;

    public static function create() {
        if ( ! isset(self::$instance)) {
            $db = new \PDO(
                sprintf('mysql:dbname=%s;host=%s',
                    'event_sourcing',
                    'localhost'
                ),
                'root',
                ''
            );

            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            self::$instance = $db;
        }

        return self::$instance;
    }
}