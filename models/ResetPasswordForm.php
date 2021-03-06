<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;

class ResetPasswordForm extends Model {

  public $password;
  private $_user;

  public function __construct($token, $config = []) {
    if (empty($token) || !is_string($token)) {
      throw new InvalidParamException('Password reset token cannot be blank.');
    }

    $this->_user = User::findByPasswordResetToken($token);

    if (!$this->_user) {
      return Yii::$app->session->setFlash('error', 'Link Kadaluarsa.');
    }

    parent::__construct($config);
  }

  public function rules() {
    return [
      ['password', 'required'],
      ['password', 'string', 'min' => 6],
    ];
  }

  public function resetPassword() {
    $user = $this->_user;
    $user->setPassword($this->password);
    $user->removePasswordResetToken();
    return $user->save(false);
  }

}
