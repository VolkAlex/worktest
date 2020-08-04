<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 03.08.2020
 * Time: 18:10
 */

namespace view;


class RegisterForm
{
    function render()
    {
        // TODO: Implement render() method.
        return <<<HTML
<form action="/?a=register" method="POST">
  <div class="form-group">
    <label for="InputLogin">Логин</label>
    <input id="InputLogin" type="text" class="form-control" name="login" required="required">
  </div>
  <div class="form-group">
    <label for="InputFio">ФИО</label>
    <input id="InputFio" type="text" class="form-control" name="fio" required="required">
  </div>
  <div class="form-group">
    <label for="InputEmail">Email</label>
    <input id="InputEmail" type="email" class="form-control" name="email" required="required">
  </div>
  <div class="form-group">
    <label for="InputPassword">Пароль</label>
    <input id="InputPassword" type="password" class="form-control" name="pass" required="required">
  </div>
  <button type="submit" class="btn btn-primary">Регистрация</button>
</form>
HTML;
    }
}