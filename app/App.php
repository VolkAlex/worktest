<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 03.08.2020
 * Time: 12:10
 */

namespace app;

use controllers\SiteController;

class App
{
    function __construct()
    {

    }

    function run()
    {
        session_start();

        (new SiteController())->getAction();

        session_write_close();
    }
}