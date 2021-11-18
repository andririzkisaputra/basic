<?php
namespace app\modules\api\controllers;

use Yii;
use \yii\rest\Controller;
use \app\modules\api\models\FormLogin;
use \app\modules\api\models\FormRegistrasi;
/**
 *
 */
class UserController extends Controller
{

  public function actionLogin() {
    $model = new FormLogin();
    if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
        return $model->getUser();
    }

    Yii::$app->response->statusCode = 404;
    return [
      'errors' => $model->errors
    ];
  }

  public function actionRegistrasi() {
    $model = new FormRegistrasi();
    if ($model->load(Yii::$app->request->post(), '') && $model->registrasi()) {
        return $model->_user;
    }

    Yii::$app->response->statusCode = 404;
    return [
      'errors' => $model->errors
    ];
  }

}

?>
