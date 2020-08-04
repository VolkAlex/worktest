<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 04.08.2020
 * Time: 11:33
 */
namespace app;

use interfaces\SingletonInterface;

class DB_PDO implements SingletonInterface
{
    private static $_instance = null;

    private function __construct () {

        $params = require __DIR__ . '/../config/db.php';

        $this->_instance = new \PDO(
            'mysql:host=' . $params['host'] . ';dbname=' . $params['name'],
            $params['user'],
            $params['pass'],
            [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]
        );
    }

    public static function getInstance()
    {
        if (self::$_instance != null) {
            return self::$_instance;
        }
        return new self;
    }

    private function __clone () {}
    private function __wakeup () {}


}