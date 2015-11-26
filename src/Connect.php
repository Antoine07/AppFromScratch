<?php

class Connect
{

    /**
     * @var null|\PDO
     */
    public static $pdo = null;


    public static function set(array $database)
    {
        try {
            $options = [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
            ];

            self::$pdo = new \PDO($database['dsn'], $database['password'], $database['username'], $options);

        } catch (\PDOException $e) {
            die ("Error line:" . $e->getLine() . "message:" . $e->getMessage());
        }
    }

}