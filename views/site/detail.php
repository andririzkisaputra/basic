<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
use yii\widgets\LinkPager;
use yii\bootstrap4\Modal;

$this->title = 'Detail';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-master-data">

  <div class="layout-data-master">
    <?php echo Html::img('@web/uploads/'.$model['gambar'], ['class' => 'detail-img']);?>
    <table class="col-lg-12 detail-info">
      <tr>
        <td class="detail-padding">Nomor Anggota</td>
        <td class="detail-padding">:</td>
        <td class="detail-padding"><?php echo $model['nomor_anggota'] ?></td>
      </tr>
      <tr class="detail-padding">
        <td class="detail-padding">Nama</td>
        <td class="detail-padding">:</td>
        <td class="detail-padding"><?php echo $model['nama'] ?></td>
      </tr>
      <tr>
        <td class="detail-padding">Status Anggota</td>
        <td class="detail-padding">:</td>
        <td class="detail-padding"><?php echo $model['status'] ?></td>
      </tr>
    </table>
  </div>
</div>
