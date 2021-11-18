<?php
namespace app\modules\api\controllers;

use Yii;
use app\modules\api\resources\KehadiranResource;
use app\modules\api\resources\AnggotaResource;
use app\modules\api\resources\UserResource;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use app\models\Api;
use yii\helpers\Url;

class KehadiranController extends ActiveController {

  public $modelClass = KehadiranResource::class;

  public function behaviors() {
    $behaviors = parent::behaviors();
    $behaviors['authenticator']['authMethods'] = [
      HttpBearerAuth::class
    ];

    return $behaviors;
  }

  public function actions()
  {
    return array_merge(
      parent::actions(),
      [
        'index' => [
          'class'          => 'yii\rest\IndexAction',
          'modelClass'     => $this->modelClass,
          'checkAccess'    => [$this, 'checkAccess'],
          'prepareDataProvider' => function ($action) {
            $baseUrl     = Yii::$app->getBasePath();
            $get         = Yii::$app->request->get();
            $query       = new Api;
            $where       = "kehadiran.created_by = ".Yii::$app->user->identity->user_id." AND kehadiran.created_at LIKE '%".date('Y-m-d')."%'";
            $where_total = "kehadiran.created_by = ".Yii::$app->user->identity->user_id;
            $where_tahun = "kehadiran.created_by = ".Yii::$app->user->identity->user_id." AND kehadiran.created_at LIKE '%".date('Y')."%'";
            $where_bulan = "kehadiran.created_by = ".Yii::$app->user->identity->user_id." AND kehadiran.created_at LIKE '%".date('Y-m')."%'";
            $total       = $query->select_join('*', 'kehadiran', 'anggota', 'kehadiran.anggota_id = anggota.anggota_id', $where_total, false,  'kehadiran.anggota_id', false);
            $tahun       = $query->select_join('*', 'kehadiran', 'anggota', 'kehadiran.anggota_id = anggota.anggota_id', $where_tahun, false,  'kehadiran.anggota_id', false);
            $bulan       = $query->select_join('*', 'kehadiran', 'anggota', 'kehadiran.anggota_id = anggota.anggota_id', $where_bulan, false,  'kehadiran.anggota_id', false);
            $kehadiran   = $query->select_join('*', 'kehadiran', 'anggota', 'kehadiran.anggota_id = anggota.anggota_id', $where, false,  'kehadiran.anggota_id', false);
            $data['data']['kehadiran'] = $kehadiran['data'];
            $data['data']['total']     = count($total['data']);
            $data['data']['tahun']     = count($tahun['data']);
            $data['data']['bulan']     = count($bulan['data']);
            $data['data']['hari']      = count($kehadiran['data']);
            return $data;
          }
        ],
        'create' => [
          'class'          => 'yii\rest\IndexAction',
          'modelClass'     => $this->modelClass,
          'checkAccess'    => [$this, 'checkAccess'],
          'prepareDataProvider' => function ($action) {
            $baseUrl = Yii::$app->getBasePath();
            $post    = Yii::$app->request->post();
            $query   = new Api;
            $anggota = AnggotaResource::find()->select('anggota_id')->where(['nomor_anggota' => $post['nomor_anggota']])->one();
            $data    = $query->simpan_kehadiran($anggota['anggota_id']);
            return $data;
          }
        ],
      ]
    );
  }

}

?>
