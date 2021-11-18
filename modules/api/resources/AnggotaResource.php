<?php
namespace app\modules\api\resources;

use app\models\Anggota;
use app\models\User;

class AnggotaResource extends Anggota {
  public function fields() {
    \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
    $response = [
      'anggota_id',
      'nama',
      'nomor_anggota',
      'gambar',
      'created_by',
      'created_at'
    ];

    return $response;
  }

  public function extraFields() {
    \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
    $response = [
      // 'status' => 'true',
      'created_by',
      'created_at'
    ];

    return $response;
  }
}

?>
