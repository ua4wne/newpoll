<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\RentLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rent-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'data')->widget(DatePicker::className(),[
        'name' => 'data',
        'options' => ['placeholder' => 'Ввод даты'],
        'value'=> date("yyyy-MM-dd",strtotime($model->data)),
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'yyyy-MM-dd',
            'autoclose'=>true,
            'weekStart'=>1, //неделя начинается с понедельника
            'startDate' => '2015-01-01', //самая ранняя возможная дата
            'todayBtn'=>true, //снизу кнопка "сегодня"
        ]
    ]) ?>

    <?= $form->field($model, 'renter_id')->dropDownList($renters,[
        'multiple' => true,
        'size' => 5,
        //'style' => 'background:gray;color:#fff;'
    ]) ?>

    <?= $form->field($model, 'allrent')->checkbox() ?>

    <?= $form->field($model, 'period')
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

    <?= $form->field($model, 'alltime')->checkbox() ?>

    <?= $form->field($model, 'notime')->checkbox() ?>


    <div class="form-group">
        <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
