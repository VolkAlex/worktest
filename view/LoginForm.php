<?php

namespace view;

use interfaces\PageInterface;

class LoginForm implements PageInterface
{
    function render()
    {
        // TODO: Implement render() method.
        return <<<HTML
<form action="/?a=login" method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Логин</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="login" required="required">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Пароль</label>
    <input type="password" class="form-control" id="exampleInputPassword1" name="pass" required="required">
  </div>
  <button type="submit" class="btn btn-primary">Войти</button>
</form>
HTML;
    }
}

