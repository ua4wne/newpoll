<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model \app\modules\main\models\Renter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="renter-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'renter_id')->dropDownList($rentsel, ['class'=>'select2 form-control','id'=>'renter_id']) ?>

    <?= $form->field($model, 'connect')->widget(DatePicker::className(),[
        'name' => 'start',
        'options' => ['placeholder' => 'Ввод даты'],
        'value'=> date("yyyy-MM-dd", strtotime($model->connect)),
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'yyyy-MM-dd',
            'autoclose'=>true,
            'weekStart'=>1, //неделя начинается с понедельника
            'startDate' => '2015-01-01', //самая ранняя возможная дата
            'todayBtn'=>true, //снизу кнопка "сегодня"
        ]
    ]) ?>

    <?= $form->field($model, 'disconnect')->widget(DatePicker::className(),[
        'name' => 'finish',
        'options' => ['placeholder' => 'Ввод даты'],
        'value'=> date("yyyy-MM-dd", strtotime($model->disconnect)),
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'yyyy-MM-dd',
            'autoclose'=>true,
            'weekStart'=>1, //неделя начинается с понедельника
            'startDate' => '2015-01-01', //самая ранняя возможная дата
            'todayBtn'=>true, //снизу кнопка "сегодня"
        ]
    ]) ?>

    <?= $form->field($model, 'ip')->dropDownList($statsel) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
    
    //select2
	$('.select2').css('width','100%').select2({allowClear:false})
	

JS;
$this->registerJs($js);
?>
