<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 03.08.2020
 * Time: 11:22
 */

spl_autoload_register('autoloader');

function autoloader($className)
{
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    require_once $_SERVER['DOCUMENT_ROOT'] .'/' . $className . '.php';
}