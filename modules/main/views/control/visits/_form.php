<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Visit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?//= $form->field($model, 'data')->textInput() ?>

    <?= $form->field($model, 'data')->widget(DateTimePicker::className(),[
    'name' => 'data',
    'type' => DateTimePicker::TYPE_INPUT,
    'options' => ['placeholder' => 'Ввод даты/времени...'],
    'convertFormat' => true,
    'value'=> date("d.m.Y"),
    'pluginOptions' => [
    'format' => 'dd.MM.yyyy',
    'autoclose'=>true,
    'weekStart'=>1, //неделя начинается с понедельника
    'startDate' => '01.01.2015', //самая ранняя возможная дата
    'todayBtn'=>true, //снизу кнопка "сегодня"
    ]
    ]) ?>

    <?= $form->field($model, 'hours')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ucount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
