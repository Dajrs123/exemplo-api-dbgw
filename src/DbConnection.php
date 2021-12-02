<?php
namespace DatabaseGateway;

class DbConnection {
    protected static $connection = null;
    public static function get() : \PDO {
        if(is_null(self::$connection)) {
            $config = include 'config/database.php';
            self::$connection = new \PDO('mysql:host=' . $config['host'] .
                ';port=' . $config['port'] .
                ';dbname=' . $config['dbname'] .
                ';charset=' . $config['charset'] , $config['user'], $config['password']);
        }
        return self::$connection;
    }
}