<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 03.08.2020
 * Time: 11:31
 */
namespace models;

use app\DB;
use controllers\SiteController;
use app\Helper;
use interfaces\SingletonInterface;

class User implements SingletonInterface
{
    private static $db;
    private static $_instance = null;

    private function __construct()
    {
        if (self::$db === null)
            self::$db = DB::getInstance();
    }

    public static function getInstance()
    {
        if (self::$_instance != null) {
            return self::$_instance;
        }
        return new self;
    }

    //тестовый пользователь для проверки
    static $testUsers = [

        [
            'login' => 'testUser',
            'fio' => 'Ivanov I.I.',
            'email' => 'testemail@mail.ru',
            'pass' => '111'
        ],
        [
            'login' => 'testUser2',
            'fio' => 'Petrov P.P.',
            'email' => 'testemail2@mail.ru',
            'pass' => '111'
        ],
    ];

    static function isLogged(){
        return isset($_SESSION['user_login']);
    }

    function getCurrentUser()
    {
        if (self::isLogged())
        {
            return self::findUserByLogin( $_SESSION['user_login'] );
        }
        else
            return false;
    }

    //авторизация
    function prepareLogin($login, $pass)
    {
        $row = $this->findUserByLogin($login);

        if ($row)
        {
            if (password_verify($pass, $row['pass']))
            {
                return true;
            }
        }

        return false;
    }

    //регистрация
    function prepareRegister($login, $pass, $fio, $email)
    {
        //авторизуем нового пользователя если всё в порядке
        $login = Helper::SQLEncode(self::$db->getLink(), $login);
        $pass = Helper::SQLEncode(self::$db->getLink(), $pass);
        $fio = Helper::SQLEncode(self::$db->getLink(), $fio);
        $email = Helper::SQLEncode(self::$db->getLink(), $email);

        $body = [
            'login'=>$login,
            'pass'=>$pass,
            'fio'=>$fio,
            'email'=>$email
        ];

        if (Helper::validateBody($body))
        {
            //проверяем дубли
            if ( $this->findUserByLogin($login) === false && $this->findUserByEmail($email) === false )
            {
                self::$db->query("INSERT INTO user (email, login, fio, pass) VALUES ('$email', '$login','$fio','".password_hash( $pass,PASSWORD_BCRYPT )."')");
                return true;
            }
            else
                return false;
        }
        else
            return false;

    }

    //изменение профиля
    function prepareProfile($fio, $pass_old, $pass)
    {
        $pass = Helper::SQLEncode(self::$db->getLink(), $pass) ;
        $pass_old = Helper::SQLEncode(self::$db->getLink(), $pass_old) ;
        $fio = Helper::SQLEncode(self::$db->getLink(), $fio);

        if (self::isLogged())
        {
            $userData = $this->findUserByLogin( $_SESSION['user_login'] );

            $verify = [];

            if ( Helper::isValue($pass_old) && Helper::isValue($pass) )
            {
                if (Helper::validate($pass_old, 'pass') === true && Helper::validate($pass, 'pass') === true)
                {
                    if ($pass_old !== $pass && password_verify($pass_old, $userData['pass']))
                    {
                        $verify[] = true;
                        $passHash = password_hash($pass, PASSWORD_BCRYPT);
                        self::$db->query("Update user set pass = '$passHash' where login = '".$userData['login']."''");
                    }
                }
                else
                    return false;
            }

            if ( Helper::isValue($fio) )
            {
                if (Helper::validate($fio, 'fio'))
                {
                    if ($fio !== $userData['fio'] && password_verify($pass_old, $userData['pass']))
                    {
                        $verify[] = true;
                        self::$db->query("Update user set fio = '".$fio."' where login = '".$userData['login']."'");
                    }
                }
                else
                    return false;
            }


            return true;
        }
        return false;
    }

    //найти такого в базе
    function findUserByLogin($login)
    {
        $login = Helper::SQLEncode(self::$db->getLink(), $login);

        $result = self::$db->db_result_one("SELECT * from user where login = '$login'");

        return $result;
    }

    //найти такого в базе
    function findUserByEmail($email)
    {
        $email = Helper::SQLEncode(self::$db->getLink(), $email);

        $result = self::$db->db_result_one("SELECT * from user where email = '$email'");

        return $result;
    }
}