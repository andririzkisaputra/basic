<?php

namespace app\controllers;

use Yii;
use app\models\Api;
use app\models\User;
use app\models\Kehadiran;
use app\models\FormRegistrasi;
use app\models\FormLogin;
use app\models\Anggota;
use app\models\FormKehadiran;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use yii\rest\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use yii\db\Query;
use yii\data\Pagination;
use yii\imagine\Image;
use Imagine\Image\Box;
// use \yii\filters\auth\HttpBasicAuth;
// use app\models\User;
// use \app\models\RestApi;

class SiteController extends Controller
{

  public function behaviors()
  {
      return [
          'verbs' => [
              'class' => VerbFilter::className(),
              'actions' => [
                  'delete' => ['post'],
              ],
          ],
      ];
  }

  public function actions() {
    return [
        'error' => [
            'class' => 'yii\web\ErrorAction',
        ],
        'captcha' => [
            'class' => 'yii\captcha\CaptchaAction',
            'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
        ],
    ];
  }

  public function actionIndex() {
    if (!Yii::$app->user->isGuest) {
      $user_id           = Yii::$app->user->identity->user_id;
      $api               = new Api;
      $model             = new FormKehadiran;
      $kehadiran         = new Query;
      $hari_ini          = date('Y-m-d');
      $bulan_ini         = date('Y-m');
      $tahun_ini         = date('Y');
      $kunjungan_total   = $kehadiran->from('kehadiran')->groupBy('anggota_id')->andWhere(['=', 'created_by', $user_id])->count();
      $kunjungan_harian  = $kehadiran->from('kehadiran')->where(['like', 'created_at', $hari_ini])->andWhere(['=', 'created_by', $user_id])->groupBy('anggota_id')->count();
      $kunjungan_bulanan = $kehadiran->from('kehadiran')->where(['like', 'created_at', $bulan_ini])->andWhere(['=', 'created_by', $user_id])->groupBy('anggota_id')->count();
      $kunjungan_tahunan = $kehadiran->from('kehadiran')->where(['like', 'created_at', $tahun_ini])->andWhere(['=', 'created_by', $user_id])->groupBy('anggota_id')->count();
      $kehadiran         = $api->select_join('kehadiran.*, anggota.nama', 'kehadiran', 'anggota', 'anggota.anggota_id = kehadiran.anggota_id', ['like', 'kehadiran.created_at', $hari_ini], ['=', 'kehadiran.created_by', $user_id], ['kehadiran.anggota_id']);
      if ($model->load(Yii::$app->request->post())) {
        if ($model->simpan_kehadiran()) {
          Yii::$app->session->setFlash('success', 'Data berhasil ditambahkan.');
          return $this->goHome();
        } else {
          Yii::$app->session->setFlash('error', 'Tidak terdaftar anggota.');
        }
      }
      return $this->render('index', [
        'kehadiran'         => $kehadiran['data'],
        'pages'             => $kehadiran['pages'],
        'model'             => $model,
        'kunjungan_total'   => $kunjungan_total,
        'kunjungan_harian'  => $kunjungan_harian,
        'kunjungan_bulanan' => $kunjungan_bulanan,
        'kunjungan_tahunan' => $kunjungan_tahunan,
      ]);
    } else {
        $this->redirect('@web/site/login');
    }
  }

  public function actionAnggota() {
    $mobile = $this->isMobile();
    if (!Yii::$app->user->isGuest) {
      $model = new Anggota;
      if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        $model->scenario = Anggota::SCENARIO_CREATE;
        $post    = Yii::$app->request->post('Anggota');
        $api     = new Api;
        $simpan  = $api->simpan_anggota($model, $post);
        if ($simpan['status'] == 'success') {
          $this->redirect('@web/site/master-data');
        }
      } else {
        return $this->render('tambah/tambahAnggota', [
          'title'   => 'Tambah Anggota',
          'model'   => $model,
          'is_edit' => false,
          'mobile'  => $mobile
        ]);
      }
    } else {
      $this->redirect('@web/site/login');
    }
  }

  public function isMobile() {
    return is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile"));
  }

  public function actionMasterData() {
    if (!Yii::$app->user->isGuest) {
      $api   = new Api;
      $model = $api->select_tabel('*', 'anggota', ['=', 'created_by', Yii::$app->user->identity->user_id], ['nomor_anggota']);

      return $this->render('masterData', [
        'model' => $model['data'],
        'pages' => $model['pages']
      ]);
    } else {
      $this->redirect('@web/site/login');
    }
  }

  public function actionDetail($id) {
    $api = new Api;
    $model = $api->select_tabel_row('*', 'anggota', ['=', 'anggota_id', $id]);
    $model['data']['status'] = ($model['data']['is_active'] == 0) ? 'Suspend' : 'Aktif';
    return $this->renderAjax('detail', [
      'model' => $model['data']
    ]);
  }

  public function actionFoto($id) {
    $api = new Api;
    $model = $api->select_tabel_row('*', 'anggota', ['=', 'anggota_id', $id]);
    $model['data']['status'] = ($model['data']['is_active'] == 0) ? 'Suspend' : 'Aktif';
    return $this->renderAjax('detail', [
      'model' => $model['data']
    ]);
  }

  public function actionEdit($id, $gambar) {

    $model = new Anggota;
    $data  = Anggota::find()->where(['anggota_id' => $id])->one();

    if ($model->load(Yii::$app->request->post())) {
      $baseUrl = Yii::$app->getBasePath();
      $model->gambar = UploadedFile::getInstance($model, 'gambar');
      $gambar = $data->gambar;
      if ($model->gambar) {
        @unlink($baseUrl.'/uploads/'.$data->gambar);
        @unlink($baseUrl.'/uploads/thumb_'.$data->gambar);
        $nama_format = strtolower($model->nama.' '.$data->nomor_anggota.' '.date('Y-m-d'));
        $nama_format = str_replace(" ", "-", $nama_format).'.'.$model->gambar->extension;
        $model->gambar->saveAs('uploads/'.$nama_format);
        $imagine = Image::getImagine();
        $image = $imagine->open('uploads/'.$nama_format);
        $image->resize(new Box(300, 400))->save('uploads/thumb_'.$nama_format, ['quality' => 70]);
        $gambar = $nama_format;
      }
      $data->attributes  = \yii::$app->request->post('Anggota');
      $data->gambar      = $gambar;
      $data->updated_at  = date('Y-m-d h:i:s');
      if ($data->save()) {
        $this->redirect('@web/site/master-data');
      }

    } else {
      return $this->renderAjax('tambah/tambahAnggota', [
        'title'   => 'Edit Anggota',
        'model'   => $model,
        'data'    => $data,
        'is_edit' => true
      ]);
    }
  }

  public function actionHapus($id, $gambar) {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $post = \yii::$app->request->post('Anggota');
    $anggota = Anggota::find()->where(['anggota_id' => $id])->one();
    if($anggota) {
      $baseUrl = Yii::$app->getBasePath();
      @unlink($baseUrl.'/uploads/'.$gambar);
      @unlink($baseUrl.'/uploads/thumb_'.$gambar);
      $anggota->delete();
      Yii::$app->session->setFlash('success', 'Data berhasil dihapus.');
      $this->redirect('@web/site/master-data');
    } else {
      Yii::$app->session->setFlash('Gagal', 'Data gagal dihapus.');
      return array('status'=>false,'data'=> 'No Student Found');
    }
  }

  public function actionLogin() {
    if (!Yii::$app->user->isGuest) {
      return $this->goHome();
    }
    $this->layout = 'blank';
    $model = new FormLogin();
    if ($model->load(Yii::$app->request->post()) && $model->login()) {
        $model->getUser()->toArray(['user_id', 'username', 'access_token']);
        return $this->goBack();
    } else {
        return $this->render('login', [
          'model' => $model,
        ]);
    }
  }

  public function actionRegistrasi() {
    $this->layout = 'blank';
    $model = new FormRegistrasi();
    if ($model->load(Yii::$app->request->post())) {
    if ($user = $model->registrasi()) {
        if (Yii::$app->getUser()->login($user)) {
            return $this->goHome();
        }
      }
    }
    return $this->render('registrasi', [
      'model' => $model,
    ]);
  }

  public function actionRequestPasswordReset()
  {
    $model = new PasswordResetRequestForm();

    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      if ($model->sendEmail()) {
        Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
        return $this->goHome();
      } else {
        Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
      }
    }

    $this->layout = 'blank';
    return $this->render('passwordResetRequestForm', [
      'model' => $model,
    ]);
  }

  public function actionResetPassword($token) {
    try {
      $model = new ResetPasswordForm($token);
    } catch (InvalidParamException $e) {
      throw new BadRequestHttpException($e->getMessage());
    }

    if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
      Yii::$app->session->setFlash('success', 'New password was saved.');
      return $this->goHome();
    }

    $this->layout = 'blank';
    return $this->render('resetPasswordForm', [
      'model' => $model
    ]);
  }

  public function actionLogout() {
    Yii::$app->user->logout();
    return $this->goHome();
  }

  public function actionUploadGambar() {
    $post        = Yii::$app->request->post();
    $data        = base64_decode($post['gambar']);
    $nama_format = strtolower($post['nama']);
    $nama_format = str_replace(" ", "-", $nama_format).'.jpg';
    return file_put_contents('uploads/'.$nama_format, $data);
  }

  public function actionApiAkses() {
    if (!Yii::$app->user->isGuest) {
      $api   = new Api;
      $model = $api->select_tabel_row('*', 'user', ['=', 'user_id', Yii::$app->user->identity->user_id]);

      return $this->render('api', [
        'model' => $model['data']
      ]);
    } else {
      $this->redirect('@web/site/login');
    }
  }

  public function actionRefreshToken() {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $security = \Yii::$app->security;
    $data = User::find()->where(['client_id' => $_GET['client_id']])->one();
    $data->access_token = $security->generateRandomString(50);
    if ($data->expires_in < date('Y-m-d H:i:s')) {
      $data->expires_in   = date('Y-m-d H:i:s', strtotime('+30 days', strtotime(date('Y-m-d H:i:s'))));
    }
    $data->save();
    $this->redirect('@web/site/api-akses');
  }

}
