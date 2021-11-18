<?php

namespace app\modules\api\models;

use Yii;
use app\modules\api\resources\UserResource;

class FormLogin extends \app\models\FormLogin {

  private $_user = false;

  public function getUser()
  {
    if ($this->_user === false) {
      $security = \Yii::$app->security;
      $this->_user = UserResource::findByUsername($this->username);
      $this->_user->access_token  = $security->generateRandomString(50);
      $this->_user->expires_in    = 3600;
      $this->_user->refresh_token = $security->generateRandomString(50);
      $this->_user->save();
    }

    return $this->_user;
  }

}
