<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\SearchVisitor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visitor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'fname') ?>

    <?= $form->field($model, 'mname') ?>

    <?= $form->field($model, 'lname') ?>

    <?= $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'renter_id') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'car_type') ?>

    <?php // echo $form->field($model, 'car_num') ?>

    <?php // echo $form->field($model, 'doc_type') ?>

    <?php // echo $form->field($model, 'doc_series') ?>

    <?php // echo $form->field($model, 'doc_num') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
