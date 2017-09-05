<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\main\models\EnergyLog */
/* @var $form ActiveForm */
?>
<div class="energy-index">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'renter_id') ?>
        <?= $form->field($model, 'year') ?>
        <?= $form->field($model, 'month') ?>
        <?= $form->field($model, 'encount') ?>
        <?= $form->field($model, 'delta') ?>
        <?= $form->field($model, 'price') ?>
        <?= $form->field($model, 'created_at') ?>
        <?= $form->field($model, 'updated_at') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- energy-index -->
