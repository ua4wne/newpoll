<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Visitor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visitor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'renter_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'car_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'car_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_series')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_num')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
