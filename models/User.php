<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
// use OAuth2\Storage\UserCredentialsInterface;
use yii\db\Expression;

class User extends ActiveRecord implements IdentityInterface {

  const STATUS_DELETED = '0';
  const STATUS_ACTIVE  = '1';

  public static function tableName() {
    return '{{%user}}';
  }

  public function behaviors() {
    return [
      TimestampBehavior::class,
      [
        'class' => BlameableBehavior::class,
        'updatedByAttribute' => false
      ],
      'timestamp' => [
          'class' => 'yii\behaviors\TimestampBehavior',
          'attributes' => [
              ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
              ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
          ],
          'value' => new Expression('NOW()'),
      ],
    ];
  }

  public function rules() {
    return [
      ['status', 'default', 'value' => self::STATUS_ACTIVE],
      ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
    ];
  }

  public function checkUserCredentials($username, $password){
    return static::findOne(['username' => $username, 'password' => $this->validatePassword($password)]);
  }

  public function getUserDetails($username) {
    return static::findOne(['username' => $username]);
  }

  public static function findIdentity($user_id) {
    return static::findOne(['user_id' => $user_id, 'status' => self::STATUS_ACTIVE]);
  }

  public static function findIdentityByAccessToken($token, $userType = null) {
    return static::find()->where(['=', 'access_token', $token])->andWhere(['>=', 'expires_in', new Expression('NOW()')])->one();
  }

  public static function findByUsername($username) {
    return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
  }

  public static function findByPasswordResetToken($token) {
    if (!static::isPasswordResetTokenValid($token)) {
        return null;
    }
    return static::findOne([
      'password_reset_token' => $token,
      'status' => self::STATUS_ACTIVE,
    ]);
  }

  public static function isPasswordResetTokenValid($token) {
    if (empty($token)) {
      return false;
    }
    $timestamp = (int) substr($token, strrpos($token, '_') + 1);
    $expire = Yii::$app->params['user.passwordResetTokenExpire'];
    return $timestamp + $expire >= time();
  }

  public static function isResetTokenValid($token) {
    if (empty($token)) {
      return false;
    }
    $timestamp = (int) substr($token, strrpos($token, '_') + 1);
    $expire = Yii::$app->params['user.passwordResetTokenExpire'];
    return $timestamp + $expire >= time();
  }

  public function getId() {
    return $this->user_id;
  }

  public function getAuthKey() {
    return $this->auth_key;
  }

  public function validateAuthKey($authKey) {
    return $this->getAuthKey() === $authKey;
  }

  public function validatePassword($password) {
    return Yii::$app->security->validatePassword($password, $this->password_hash);
  }

  public function setPassword($password) {
    $this->password_hash = Yii::$app->security->generatePasswordHash($password);
  }

  public function generateAuthKey() {
    $this->auth_key = Yii::$app->security->generateRandomString(40);
  }

  public function generateAccessToken() {
    $this->access_token = Yii::$app->security->generateRandomString(40);
  }

  public function generatePasswordResetToken() {
    $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
  }

  public function removePasswordResetToken() {
    $this->password_reset_token = null;
  }

}
