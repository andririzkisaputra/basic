<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Anggota;
use app\models\User;
use yii\authclient\BaseOAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\authclient\OAuth2;

class RestController extends ActiveController {

  public $modelClass = 'app\models\Anggota';

  public function behaviors() {
    $behaviors = parent::behaviors();
    $behaviors['authenticator'] = [
      'class' => HttpBasicAuth::className(),
      'auth' => function ($username, $password) {
        $user = User::findByUsername($username);
        if(!is_null($user)){
          if ($user->validatePassword($password)) {
            return $user;
          }
        }

        return null;
      },
    ];
    return $behaviors;
  }

  public function actionCreateAnggota() {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $anggota             = new Anggota();
    $anggota->scenario   = Anggota::SCENARIO_CREATE;
    $nomor_anggota       = rand(10000, 99999);
    // $nama_format = strtolower($this->nama.' '.$nomor_anggota.' '.date('Y-m-d'));
    // $nama_format = str_replace(" ", "-", $nama_format).'.'.$this->gambar->extension;
    // $this->gambar->saveAs('uploads/'.$nama_format);
    // $imagine = Image::getImagine();
    // $image = $imagine->open('uploads/'.$nama_format);
    // $image->resize(new Box(300, 400))->save('uploads/thumb_'.$nama_format, ['quality' => 70]);

    $anggota->attributes    = \yii::$app->request->post();
    $anggota->nomor_anggota = $nomor_anggota;
    $anggota->created_by    = '24';
    $anggota->created_on    = date('Y-m-d H:s:i');

    if ($anggota->validate()) {
      $anggota->save();
      return array(
        'status' => true,
        'data'   => 'Anggota record is successfully updated'
      );
    } else {
      return array(
        'status' => false,
        'data'   => $anggota->getErrors()
      );
    }
  }

  public function actionGetAnggota() {
    \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

    $anggota = Anggota::find()->where(['created_by' => '24'])->all();
    if(count($anggota) > 0 ) {
      return array(
        'status' => true,
        'data'   => $anggota
      );
    } else {
      return array(
        'status' => false,
        'data'   => 'No Anggota Found'
      );
    }
  }

  public function actionUpdateAnggota() {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $get    = \yii::$app->request->get();
    $anggota = Anggota::find()->where(['anggota_id' => $get['anggota_id']])->one();

    if($anggota) {
      if ($get['gambar']) {
        $gambar = $get['gambar'];
      } else {
        $gambar = $anggota->gambar;
      }
      $anggota->attributes  = \yii::$app->request->get();
      $anggota->gambar      = $gambar;
      $anggota->modified_on = date('Y-m-d H:s:i');
      $anggota->save();
      return array(
        'status' => true,
        'data'   => 'Anggota record is updated successfully'
      );
    } else {
      return array(
        'status' => false,
        'data'   => 'No Anggota Found'
      );
    }
  }

  public function actionDeleteAnggota() {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $post = \yii::$app->request->post();
    $anggota = Anggota::find()->where(['anggota_id' => $post['anggota_id']])->one();
    if($anggota) {
      $anggota->delete();
      return array('status' => true, 'data'=> 'Anggota record is successfully deleted');
    } else {
      return array('status'=>false,'data'=> 'No Anggota Found');
    }
  }

}
