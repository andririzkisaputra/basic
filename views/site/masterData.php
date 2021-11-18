<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
use yii\widgets\LinkPager;
use yii\bootstrap4\Modal;

$this->title = 'Master Data';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-master-data">

  <div class="layout-data-master">
    <h3 class="text-black font-weight-bold">Data Anggota</h3>
    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>No</th>
          <th>Nomor Anggota</th>
          <th>Nama</th>
          <th>Tanggal Daftar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($model) {
          $i = 1;
          foreach ($model as $value) : ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo $value['nomor_anggota']; ?></td>
              <td><?php echo $value['nama']; ?></td>
              <td><?php echo date('H:i:s', strtotime($value['created_at'])) ?></td>
              <td>
                <div class="form-group">
                  <?= Html::button('Detail', ['value' => Url::to(['detail','id' => $value['anggota_id']]), 'class' => 'btn btn-sm btn-info showModalButton']); ?>
                  <?= Html::button('Edit', ['value' => Url::to(['edit','id' => $value['anggota_id'], 'gambar' => $value['gambar']]), 'class' => 'btn btn-sm btn-success showModalButton']); ?>
                  <?= Html::a('Hapus', ['site/hapus?id='.$value['anggota_id'].'&gambar='.$value['gambar']], ['class' => 'btn btn-sm btn-danger']); ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php } ?>
      </tbody>
      <thead>
        <tr>
          <th>No</th>
          <th>Nomor Anggota</th>
          <th>Nama</th>
          <th>Tanggal Daftar</th>
          <th>Aksi</th>
        </tr>
      </thead>
    </table>
    <?php

    if ($model) {
      echo LinkPager::widget([
        'pagination'         => $pages,
        'prevPageLabel'      => false,
        'nextPageLabel'      => false,
        'maxButtonCount'     => 5,
        'activePageCssClass' => 'active-page',
        'firstPageLabel'     => 'First',
        'firstPageCssClass'  => 'page-prev-last',
        'lastPageCssClass'   => 'page-prev-last',
        'pageCssClass'       => 'page-prev-last',
        'lastPageLabel'      => 'Last',
      ]);
    }
    ?>
  </div>
  <?php
    Modal::begin([
      'id'=>'modal',
      'size'=>'modal-md',
    ]);

    echo "<div id='modalContent'></div>";

    Modal::end();
  ?>
</div>
