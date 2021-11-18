<?php

namespace app\modules\api\models;

use Yii;
use yii\base\Model;
use app\modules\api\resources\UserResource;

class FormRegistrasi extends Model {

  public $nama;
  public $username;
  public $email;
  public $password;

  public $_user = false;

  public function rules() {
    return [
      ['nama', 'required'],
      ['nama', 'string', 'max' => 255],
      ['username', 'trim'],
      ['username', 'required'],
      ['username', 'unique', 'targetClass' => '\app\modules\api\resources\UserResource', 'message' => 'This username has already been taken.'],
      ['username', 'string', 'min' => 2, 'max' => 255],
      ['email', 'trim'],
      ['email', 'required'],
      ['email', 'email'],
      ['email', 'string', 'max' => 255],
      ['email', 'unique', 'targetClass' => '\app\modules\api\resources\UserResource', 'message' => 'This email address has already been taken.'],
      ['password', 'required'],
      ['password', 'string', 'min' => 6],
    ];
  }

  public function registrasi() {

    $this->_user = new UserResource();
    if (!$this->validate()) {
        return false;
    }
    $security = \Yii::$app->security;
    $this->_user->nama          = $this->nama;
    $this->_user->username      = $this->username;
    $this->_user->email         = $this->email;
    $this->_user->setPassword($this->password);
    $this->_user->generateAuthKey();
    $this->_user->client_id     = $security->generateRandomString(15);
    $this->_user->client_secret = $security->generateRandomString(40);
    $this->_user->token_type    = 'Bearer';
    $this->_user->scope         = 'app';
    return $this->_user->save() ? true : false;
  }

}
