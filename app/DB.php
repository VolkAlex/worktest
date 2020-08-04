<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 03.08.2020
 * Time: 11:36
 */

namespace app;

use interfaces\SingletonInterface;

class DB implements SingletonInterface
{
    private static $_instance = null;
    private $connection = null;
    private $error = false;

    private function __construct()
    {
        if($this->connection === null)
        {
            $params = require __DIR__ . '/../config/db.php';

            $connection = new \mysqli($params['host'], $params['login'], $params['pass'], $params['name'], $params['port']);

            if ($connection->connect_error === null){
                $connection->set_charset("utf8");
                $this->connection = $connection;
                //print ('connect!');
            }
            else {
                $this->error = true;
                die('connect error');
            }
        }
        return $this->connection;
    }

    public static function getInstance()
    {
        if (self::$_instance != null) {
            return self::$_instance;
        }
        return new self;
    }

    function getLink()
    {
        return $this->connection;
    }

    function disconnect() {
        if ($this->connection !== null) {
            $this->connection->close();
            unset($this->connection);
        }
    }

    function query($sql) {
        $result = $this->connection->query($sql) or print('sql error');
        //var_dump($result);
        return $result;
    }

    //строка в ассоциативный массив (число элементов = числу строк)
    function mysql_fetch_array ($sql)
    {
        $query = $this->query($sql);
        $result = $query->fetch_array(MYSQLI_ASSOC);

        if (is_array($result) && count($result)>0)
        {
            return $result;
        }
        else return false;
    }

    //массив из строк по номерам
    function db_result_to_array($sql)
    {
        $query = $this->query($sql);
        $result = $query->fetch_array(MYSQLI_ASSOC);

        if (is_array($result) && count($result)>0)
        {
            $res_array = array();
            for ($count=0; $row = $result; $count++)
                $res_array[$count] = $row;
            return $res_array;
        }
        else return false;

    }

    //первая строка результата
    function db_result_one($sql)
    {
        $result = $this->mysql_fetch_array($sql);

        if (is_array($result) && count($result)>0)
            return $result;
        else return false;
    }
}