<?php

namespace Infraweb\App\DB;

use PDO;

class MysqlConnection
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (is_null(self::$instance)) {
            self::$instance = new PDO(
                'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_SCHEMA'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD'],
                [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_CASE => PDO::CASE_NATURAL,
                ]
            );
        }

        return self::$instance;
    }
}
