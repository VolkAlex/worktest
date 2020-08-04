<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 03.08.2020
 * Time: 18:44
 */
namespace view;

use models\User;

class ProfileForm
{
    function render()
    {
        $user = User::getInstance();
        $currentUser = $user->getCurrentUser();

        // TODO: Implement render() method.
        return <<<HTML
<form action="/?a=profile" method="POST">

  <div class="form-group">
    <label for="exampleInputEmail1">ФИО</label>
    <input type="text" class="form-control" name="fio" value="{$currentUser['fio']}">
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Старый пароль</label>
    <input type="password" class="form-control" name="pass_old">
  </div>
  
  <div class="form-group">
    <label for="exampleInputPassword1">Новый Пароль</label>
    <input type="password" class="form-control" name="pass">
  </div>
  
  <button type="submit" class="btn btn-primary">Сохранить</button>
</form>
HTML;
    }
}
