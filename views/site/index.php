<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
use yii\widgets\LinkPager;

$this->title = 'Home';
?>
<div class="site-index">
  <div class="row" style="margin-bottom: 10px">
    <div class="total-tamu" style="background-color: #20a8d8;">
      <h3 class="text-white font-weight-bold"><?= Html::encode($kunjungan_total) ?></h3>
      <p class="text-white">Total Kunjungan</p>
    </div>
    <div class="total-tamu" style="background-color: #63c2de;">
      <h3 class="text-white font-weight-bold"><?= Html::encode($kunjungan_tahunan) ?></h3>
      <p class="text-white">Kunjungan Tahun Ini</p>
    </div>
    <div class="total-tamu" style="background-color: #f6ca04;">
      <h3 class="text-white font-weight-bold"><?= Html::encode($kunjungan_bulanan) ?></h3>
      <p class="text-white">Kunjungan Bulan Ini</p>
    </div>
    <div class="total-tamu" style="background-color: #f86c6b;">
      <h3 class="text-white font-weight-bold"><?= Html::encode($kunjungan_harian) ?></h3>
      <p class="text-white">Kunjungan Hari Ini</p>
    </div>
    <div class=" row form-tamu">
      <div class="layout-form-tamu">
        <h3 class="text-black font-weight-bold">Form Tamu</h3>
        <?php $form = ActiveForm::begin([
          'id' => 'login-form',
          'options' => ['class' => 'form-horizontal'],
        ]); ?>
          <?= $form->field($model, 'nomor_anggota') ?>
          <div class="form-group">
              <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
          </div>
        <?php ActiveForm::end(); ?>
      </div>
      <div class="layout-form-tamu">
        <h3 class="text-black font-weight-bold">Data Tamu</h3>
        <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Waktu</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($kehadiran) {
              $i = 1;
              foreach ($kehadiran as $value) : ?>
                <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $value['nama']; ?></td>
                  <td><?php echo date('H:i:s', strtotime($value['created_at'])) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php } ?>
          </tbody>
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Waktu</th>
            </tr>
          </thead>
        </table>
        <?php

        if ($kunjungan_harian > 0) {
          echo LinkPager::widget([
            'pagination'         => $pages,
            'prevPageLabel'      => false,
            'nextPageLabel'      => false,
            'maxButtonCount'     => 2,
            'activePageCssClass' => 'active-page',
            'firstPageLabel'     => 'Prev',
            'firstPageCssClass'  => 'page-prev-last',
            'lastPageCssClass'   => 'page-prev-last',
            'pageCssClass'       => 'page-prev-last',
            'lastPageLabel'      => 'Next',
          ]);
        }
        ?>
      </div>
    </div>
  </div>
</div>
