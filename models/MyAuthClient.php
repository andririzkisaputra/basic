<?php
use yii\authclient\OAuth2;

class MyAuthClient extends OAuth2
{
  public $authUrl = 'https://www.my.com/oauth2/auth';

  public $tokenUrl = 'https://www.my.com/oauth2/token';

  public $apiBaseUrl = 'https://www.my.com/apis/oauth2/v1';

  protected function initUserAttributes()
  {
      return $this->api('userinfo', 'GET');
  }
}

?>
