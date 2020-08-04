<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 03.08.2020
 * Time: 12:21
 */

namespace controllers;

use app\Controller;
use app\Helper;
use view\Page;
use models\User;
use view\LoginForm;
use view\RegisterForm;
use view\ProfileForm;

class SiteController extends Controller
{
    public $view = 'main';
    static $messages = []; //служебные уведомления

    private static function mPush($s)
    {
        array_push(self::$messages, $s);
    }

    static function post($key, $unset = true)
    {
        if (isset($_POST[$key]) && strlen(trim($_POST[$key])) > 0)
        {
            $val = $_POST[$key];
            if ($unset) unset($_POST[$key]);
            return $val;
        }
        else return null;
    }

    function actionDefault()
    {
        $user = User::getInstance();

        if ($user::isLogged())
        {
            $currentUser = $user->getCurrentUser();

            $hello = 'Вы вошли как '.$currentUser['login'];
        }
        else
            $hello = 'Вы вошли как гость';

        $this->render($hello);
    }

    function actionTest()
    {
        $this->render('test');
    }

    public function actionLogout()
    {
        session_unset();
        session_destroy();

        $this->goHome();
    }

    public function actionLogin()
    {
        if (User::isLogged())
        {
            $this->goHome();
        }

        $form = (new LoginForm())->render();

        $login =  self::post('login');
        $pass =  self::post('pass');

        if ($login !== null && $pass !== null)
        {
            $login = Helper::Encode( $login );
            $pass = Helper::Encode( $pass );

            $user = User::getInstance();

            //успешная авторизация
            if ($user->prepareLogin($login, $pass))
            {
                $_SESSION['user_login'] = $login;

                $this->goHome();
            }
            //некорректные данные, выводим сообщение об ошибке и чистим тело POST (очищено заранее при перехвате переменной)
            else
            {
                self::mPush('авторизация не удалась, некорректные данные');
            }
        }

        $this->render($form);
    }



    public function actionRegister()
    {
        if (User::isLogged())
        {
            $this->goHome();
        }

        $form = (new RegisterForm())->render();

        $login = self::post('login');
        $pass = self::post('pass');
        $fio = self::post('fio');
        $email = self::post('email');

        if ($login !== null || $pass !== null || $fio !== null || $email !== null)
        {
            $login = Helper::Encode( $login );
            $pass = Helper::Encode( $pass );
            $fio = Helper::Encode( $fio );
            $email = Helper::Encode( $email );

            $user = User::getInstance();

            //успешная регистрация
            if ($user->prepareRegister($login, $pass, $fio, $email))
            {
                $_SESSION['user_login'] = $login;

                $this->goHome();
            }
            else
            {
                self::mPush('регистрация не удалась, некорректные данные');
            }
        }

        $this->render($form);
    }

    public function actionProfile()
    {
        if (!User::isLogged())
        {
            $this->actionLogin();
        }
        else
        {
            $form = (new ProfileForm())->render();

            $pass_old = self::post('pass_old');
            $pass = self::post('pass');
            $fio = self::post('fio');

            if ( (Helper::isValue($pass_old) && Helper::isValue($pass)) || Helper::isValue($fio))
            {
                $pass_old = Helper::Encode( $pass_old );
                $pass = Helper::Encode( $pass );
                $fio = Helper::Encode( $fio );

                $user = User::getInstance();
                //успешная регистрация
                if ($user->prepareProfile($pass_old, $pass, $fio))
                {
                    $this->goHome();
                }
                else
                {
                    self::mPush('изменения не сохранены, некорректные данные');
                }
            }


            $this->render($form);
        }
    }

    function render($content)
    {
        $page = new Page($content);
        echo $page->render();
    }
}