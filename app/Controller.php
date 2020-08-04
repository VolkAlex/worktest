<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 03.08.2020
 * Time: 13:23
 */

namespace app;

abstract class Controller
{
    public $action;
    //разбор входящего урла для подбора view

    function __construct()
    {
        $this->setAction();
    }

    private function setAction()
    {
        $this->action = (isset($_GET['a']))? $_GET['a'] : null;
    }

    function getAction()
    {
        $method = 'action'.ucfirst($this->action);

        if (method_exists($this, $method))
        {
            return $this->$method();
        }
        else
        {
            return $this->actionDefault();
        }
    }

    function goHome()
    {
        header("Location: //".$_SERVER['HTTP_HOST'], true, 301);
    }

    abstract function actionDefault();

}