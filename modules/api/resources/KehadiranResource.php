<?php
namespace app\modules\api\resources;

use app\models\Kehadiran;

class KehadiranResource extends Kehadiran {
  public function fields() {
    $response = [
      'kehadiran_id',
      'nomor_anggota',
      'created_by',
      'created_at',
      'updated_at'
    ];
    return $response;
  }
}

?>
