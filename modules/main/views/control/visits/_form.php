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
        'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
        'options' => ['placeholder' => 'Ввод даты/времени...'],
        'value'=> date("yyyy-MM-dd",$model->data),
        'convertFormat' => true,
        'value' => date("Y-m-d"),
        'pluginOptions' => [
        'format' => 'yyyy-MM-dd',
        'autoclose'=>true,
        'weekStart'=>1, //неделя начинается с понедельника
        'startDate' => '2015-01-01', //самая ранняя возможная дата
        'todayBtn'=>true, //снизу кнопка "сегодня"
    ]
    ]) ?>

    <?//= $form->field($model, 'hours')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hours')
        ->listBox([
            '10'=>'С 10:00 до 11:00','11'=>'С 11:00 до 12:00','12'=>'С 12:00 до 13:00',
            '13'=>'С 13:00 до 14:00','14'=>'С 14:00 до 15:00','15'=>'С 15:00 до 16:00',
            '16'=>'С 16:00 до 17:00','17'=>'С 17:00 до 18:00','18'=>'С 18:00 до 19:00',
            '19'=>'С 19:00 до 20:00','20'=>'С 20:00 до 21:00'
        ],
            [
                'multiple' => true,
                'size' => 11,
                //'style' => 'background:gray;color:#fff;'
            ]) ?>

    <?= $form->field($model, 'ucount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
