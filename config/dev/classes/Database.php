<?php
    namespace Techy\Config;

    use Techy\Lib\Core\Database\Config;

    class Database extends Config{
        public static $databases = array(
            'main' => array(
                'driver' => 'PostgreSQL',
                'host' => 'localhost',
                'port' => '5432',
                'user' => 'dev',
                'password' => '123456',
                'dbname' => 'techydb',
                'charset' => 'UTF8',
            ),
        );
    }
