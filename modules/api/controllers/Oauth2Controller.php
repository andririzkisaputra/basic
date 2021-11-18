<?php
namespace app\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use app\modules\api\resources\UserResource;
use yii\db\Expression;
/**
 *
 */
class Oauth2Controller extends Controller
{

  public function actionCallback() {
    return false;
  }

  public function actionAuthorize() {
    $user = new UserResource;
    return $_GET['client_id'];
  }

  public function actionToken() {
    $update = UserResource::findOne([
      'client_id'  => $_GET['client_id'],
    ]);
    $update->access_token = Yii::$app->security->generateRandomString(40);
    $update->expires_in   = new Expression('NOW() + INTERVAL 30 DAY');
    $update->save();
    return $update;
  }

}

?>
