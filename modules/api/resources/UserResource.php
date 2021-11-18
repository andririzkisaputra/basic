<?php
namespace app\modules\api\resources;

use app\models\User;

class UserResource extends User {
  public function fields() {
    $response = [
      'access_token',
      'token_type',
      'expires_in',
      'scope',
    ];
    return $response;
  }
}

?>
