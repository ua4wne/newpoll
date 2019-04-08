<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\main\models\Renter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="renter-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agent')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'encounter')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'koeff')->textInput(['value' => Yii::$app->params['renter_koeff']]) ?>

    <?= $form->field($model, 'status')->dropDownList($statsel) ?>

    <?= $form->field($model, 'place_id')->dropDownList($place) ?>

    <?= $form->field($model, 'division_id')->dropDownList($division) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
