<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Request password reset';
?>

<div class="site-signup form-data">
  <h1><?= Html::encode($this->title) ?></h1>
  <div class="row">
    <div class="col-lg-12" >
      <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
        <div class="form-group">
            <?= Html::submitButton('Kirim', ['class' => 'btn btn-primary']) ?>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
