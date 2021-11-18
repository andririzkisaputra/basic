<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\User;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppController extends Controller {

  public function actionAddUser($username, $nama, $email, $password) {
    $security = \Yii::$app->security;
    $user = new User;
    $user->username      = $username;
    // $user->password_hash = $security->generatePasswordHash($password);
    $user->email         = $this->email;
    $user->setPassword($this->password);
    $user->generateAuthKey();
    $user->access_token  = $security->generateRandomString(255);
    if ($user->save()) {
      Console::output('Saved');
    } else {
      var_dump($user->errors);
      Console::output('Not Saved');
    }
  }

}
