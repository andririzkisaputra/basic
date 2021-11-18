<?php

namespace app\models;

use Yii;
use yii\base\Model;

class FormKehadiran extends Model {

  public $nomor_anggota;

  public function rules() {
    return [
      ['nomor_anggota', 'required']
    ];
  }

  public function simpan_kehadiran() {
    if ($this->validate()) {
      $api  = new Api;
      $anggota = Anggota::find()->where(['=', 'nomor_anggota', $this->nomor_anggota])->one();
      $simpan = $api->simpan_kehadiran($anggota['anggota_id'], Yii::$app->user->identity->user_id);
      return ($simpan['status'] == 'sukses') ? true : false;
    } else {
      return false;
    }
  }

}
