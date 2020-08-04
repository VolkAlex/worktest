<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 04.08.2020
 * Time: 11:08
 */

namespace app;

class Helper
{
    //предотвращение XSS
    static function Encode($content, $doubleEncode = true)
    {
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);
    }

    //предотвращение SQL-инъекций
    static function SQLEncode($link, $value)
    {
        return mysqli_real_escape_string($link, $value);
    }

    //валидация полей
    static function validate($value, $type, $off = false)
    {
        if ($off) return true;

        switch ($type)
        {
            //логин 2-20 символов латинницей и цифрами, сначала буква латинницы
            case 'login':
                {
                    if ( boolval(preg_match('/^[A-Z][A-Z0-9]{2,20}$/i', $value)) === true) return true;
                    else return false;
                }
                break;

            //проверка для сильного пароля: должен состоять из 8-30 символов, состоящих из букв латинницы верхнего и нижнего регистра и цифр
            case 'pass':
                {
                    if ( preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,30}/', $value) ) return true;
                    else  return false;
                }
                break;

            case 'email':
                {
                    if ( boolval(preg_match('/^(\w+@\w+?\.[a-z]{2,})$/i', $value)) ) return true;
                    else return false;
                }
                break;

            case 'fio':
                {
                    return true;

                    //полное ФИО
//                    if (boolval(preg_match('/^[А-Я]{2,20}\s[А-Я]{2,20}\s[А-Я]{2,20}$/i', $value))) return true;
//                    else
//                    {
//                        //сокращенное ФИО
//                        if ( preg_match('/^[А-Я]{2,20}\s[А-Я]{1}\.\s?+[А-Я]{1}\.$/i', $value) ) return true;
//                        else return false;
//                    }
                }
                break;

            default: return false;
        }
    }


    static function validateBody($arrayField) //array keys
    {
        $validation = [];

        foreach ($arrayField as $key => $field)
            $validation[] = Helper::validate($field, $key);

        if (in_array(false, $validation))
            return false;
        else return true;
    }

    //присвоено ли значение
    static function isValue($val)
    {
        return ($val !== null && $val !== "");
    }

}