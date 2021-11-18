<?php
namespace app\modules\oauth2\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\MyAuthClient;
use yii\filters\AccessControl;
// use yii\authclient\OAuth2;
use app\models\FormLogin;
// use app\models\FormRegistrasi;

class TokenController extends Controller {
  private $client_id;
  private $redirect_uri = 'http://localhost/buku_pengunjung/api/oauth2/callback';
  private $data;
  private $token;

  public function actionIndex() {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $security = \Yii::$app->security;
    // $this->data = User::find()->where(['client_id' => $this->client_id])->one();
    $this->token['access_token'] = $security->generateRandomString(50);
    return $this->token ? $this->token : null;
  }

}
