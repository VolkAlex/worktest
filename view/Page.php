<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 03.08.2020
 * Time: 12:05
 */

namespace view;

use controllers\SiteController;
use interfaces\PageInterface;
use models\User;

class Page implements PageInterface
{
    public $content;

    function __construct($content)
    {
        $this->content = $content;
    }

    function render()
    {
        $msg = '';

        if (count(SiteController::$messages) > 0)
        {
            $msg = implode(', ', SiteController::$messages);
        }

        $nav = '<div class="container">
            <ul class="nav">';

        if (User::isLogged())
        {
            $nav .= '<li class="nav-item">
                    <a class="nav-link" href="/index.php?a=profile">Личный кабинет</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/index.php?a=logout">Выход</a>
                </li>';
        }
        else
        {
            $nav .= '<li class="nav-item">
                    <a class="nav-link active" href="/index.php?a=register">Регистрация</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/index.php?a=login">Вход</a>
                </li>';
        }

        $nav .= '</ul>
        </div>';

        return <<<HTML
<html>
    <title>Work test</title>
    <head>
        <link rel="stylesheet" href="web/css/bootstrap-reboot.css">
        <link rel="stylesheet" href="web/css/bootstrap.css">
        <link rel="stylesheet" href="web/css/bootstrap-grid.css">
    </head>
    <body>
    
        {$nav}

        <div class="container">
            <p>{$msg}</p>
            {$this->content}
        </div>

        <script src="web/js/bootstrap.js"></script>
        <script src="web/js/bootstrap.bundle.js"></script
    </body>
</html>
HTML;

    }
}