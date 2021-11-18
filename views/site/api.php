<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
use yii\widgets\LinkPager;
use yii\bootstrap4\Modal;

$this->title = 'Api';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-master-data">
  <div class="layout-data-master">
    <h3 class="text-black font-weight-bold">Data Api</h3>
    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
      <thead>
      <tr>
        <?= Html::tag('td', Html::encode('client_id'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode($model['client_id'])) ?>
      </tr>
      <tr>
        <?= Html::tag('td', Html::encode('client_secret'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode($model['client_secret'])) ?>
      </tr>
      <tr>
        <?= Html::tag('td', Html::encode('expires_in'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode($model['expires_in'])) ?>
      </tr>
      <tr>
        <?= Html::tag('td', Html::encode('akses_token'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode($model['access_token'])) ?>
      </tr>
      <tr>
        <?= Html::tag('td', Html::encode('type'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode($model['token_type'])) ?>
      </tr>
      <tr>
        <?= Html::tag('td', Html::encode('status'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode(($model['expires_in'] < date('Y-m-d H:i:s')) ? 'Expired' : 'Active'), ['class' => ($model['expires_in'] < date('Y-m-d H:i:s')) ? 'btn btn-sm btn-danger' : 'btn btn-sm btn-info']) ?>
      </tr>
    </table>
    <?= Html::a('Refresh Akses Token', ['site/refresh-token?client_id='.$model['client_id']], ['class' => 'btn btn-sm btn-info']); ?>
  </div>
  <div class="layout-data-master">
    <h3 class="text-black font-weight-bold">Dokumentasi Api Anggota</h3>
    <?= Html::tag('p', Html::encode('Endpoint Anggota'), ['class' => 'font-weight-bold']) ?>
    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
      <thead>
      <tr>
        <?= Html::tag('td', Html::encode('URL_GET'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode('http://localhost/buku_pengunjung/api/anggota')) ?>
      </tr>
      <tr>
        <?= Html::tag('td', Html::encode('URL_POST'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode('http://localhost/buku_pengunjung/api/anggotas')) ?>
      </tr>
      <tr>
        <?= Html::tag('td', Html::encode('URL_PUT'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode('http://localhost/buku_pengunjung/api/anggota/update?id=anggota_id')) ?>
      </tr>
      <tr>
        <?= Html::tag('td', Html::encode('URL_DELETE'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode('http://localhost/buku_pengunjung/api/anggota/delete?id=anggota_id')) ?>
      </tr>
    </table>
    <?= Html::tag('p', Html::encode('Method'), ['class' => 'font-weight-bold']) ?>
    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
      <thead>
      <tr>
        <?= Html::tag('td', Html::encode('Method'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode('POST, GET, PUT, DELETE')) ?>
      </tr>
    </table>
    <?= Html::tag('p', Html::encode('Auth'), ['class' => 'font-weight-bold']) ?>
    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
      <thead>
      <tr>
        <?= Html::tag('td', Html::encode('Token Akses'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode($model['access_token'])) ?>
      </tr>
    </table>
    <?= Html::tag('p', Html::encode('Header'), ['class' => 'font-weight-bold']) ?>
    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
      <thead>
      <tr>
        <?= Html::tag('td', Html::encode('Content-Type'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode('application/json')) ?>
      </tr>
      <tr>
        <?= Html::tag('td', Html::encode('Authorization'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode('Bearer')) ?>
      </tr>
    </table>
    <?= Html::tag('p', Html::encode('Body POST dan DELETE Anggota'), ['class' => 'font-weight-bold']) ?>
    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
      <thead>
      <tr>
        <?= Html::tag('td', Html::encode('nama'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode('varchar(45)')) ?>
      </tr>
    </table>
  </div>
  <div class="layout-data-master">
    <h3 class="text-black font-weight-bold">Dokumentasi Api Kehadiran</h3>
    <?= Html::tag('p', Html::encode('Endpoint Anggota'), ['class' => 'font-weight-bold']) ?>
    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
      <thead>
      <tr>
        <?= Html::tag('td', Html::encode('URL_GET'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode('http://localhost/buku_pengunjung/api/kehadiran')) ?>
      </tr>
      <tr>
        <?= Html::tag('td', Html::encode('URL_POST'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode('http://localhost/buku_pengunjung/api/kehadirans')) ?>
      </tr>
    </table>
    <?= Html::tag('p', Html::encode('Method'), ['class' => 'font-weight-bold']) ?>
    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
      <thead>
      <tr>
        <?= Html::tag('td', Html::encode('Method'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode('POST, GET')) ?>
      </tr>
    </table>
    <?= Html::tag('p', Html::encode('Auth'), ['class' => 'font-weight-bold']) ?>
    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
      <thead>
      <tr>
        <?= Html::tag('td', Html::encode('Token Akses'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode($model['access_token'])) ?>
      </tr>
    </table>
    <?= Html::tag('p', Html::encode('Header'), ['class' => 'font-weight-bold']) ?>
    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
      <thead>
      <tr>
        <?= Html::tag('td', Html::encode('Content-Type'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode('application/json')) ?>
      </tr>
      <tr>
        <?= Html::tag('td', Html::encode('Authorization'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode('Bearer')) ?>
      </tr>
    </table>
    <?= Html::tag('p', Html::encode('Body POST Kehadiran'), ['class' => 'font-weight-bold']) ?>
    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
      <thead>
      <tr>
        <?= Html::tag('td', Html::encode('nomor_anggota'), ['class' => 'font-weight-bold']) ?>
        <?= Html::tag('td', Html::encode(':')) ?>
        <?= Html::tag('td', Html::encode('varchar(45)')) ?>
      </tr>
    </table>
  </div>
</div>
