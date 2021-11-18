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

class AuthorizeController extends Controller {
  private $client_id;
  private $redirect_uri = 'http://localhost/buku_pengunjung/api/oauth2/callback';
  private $data;
  private $token;

  public function actionIndex() {
    $this->layout       = 'blank';
    $model              = new FormLogin();
    $this->client_id    = @$_GET['client_id'];
    $this->redirect_uri = ($this->redirect_uri == @$_GET['redirect_uri']) ? true : false;
    $this->data = User::find()->where(['client_id' => $this->client_id])->one();

    if ($this->data && $this->client_id && $this->redirect_uri) {
      if ($model->load(Yii::$app->request->post()) && $model->login()) {
        // $user = Yii::$app->request->post('FormLogin');
        // $security = \Yii::$app->security;
        // $this->data = User::find()->where(['username' => $user['username']])->one();
        // $this->data->access_token = $security->generateRandomString(50);
        // $this->data->save();
        // $data = [
        //   'access_token'  => $this->data->access_token,
        //   'token_type'    => $this->data->token_type,
        //   'expires_in'    => $this->data->expires_in,
        //   'scope'         => $this->data->scope,
        //   'refresh_token' => $this->data->refresh_token,
        // ];
        $module = Yii::$app->getModule('oauth2');
        $response = $module->getServer()->handleAuthorizeRequest(null, null, !Yii::$app->getUser()->getIsGuest(), Yii::$app->getUser()->getId());
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $module;
      } else {
        return $this->render('../site/login', [
          'model' => $model,
        ]);
      }
    } else {
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $this->data = [
        "data" =>
        [
          "error"   => "redirect_uri_mismatch",
          "request" => "\/oauth2\/authorize",
          "method"  => "GET"
        ],
        "success" => false,
        "status"  => 400
      ];
    }
    return $this->data;
  }

}
