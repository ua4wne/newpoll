<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\RentLog */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Присутствие арендаторов на выставке';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="loader"></div> <!--  идентификатор загрузки (анимация) - ожидания выполнения-->
<div class="work-report-form">
    <h1>Задайте условия отбора</h1>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'start')->widget(DatePicker::className(),[
        'name' => 'start',
        'options' => ['placeholder' => 'Ввод даты'],
        'value'=> date("yyyy-MM-dd", strtotime($model->start)),
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'yyyy-MM-dd',
            'autoclose'=>true,
            'weekStart'=>1, //неделя начинается с понедельника
            'startDate' => '2015-01-01', //самая ранняя возможная дата
            'todayBtn'=>true, //снизу кнопка "сегодня"
        ]
    ]) ?>

    <?= $form->field($model, 'finish')->widget(DatePicker::className(),[
        'name' => 'finish',
        'options' => ['placeholder' => 'Ввод даты'],
        'value'=> date("yyyy-MM-dd", strtotime($model->finish)),
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

    <div class="form-group">
        <?= Html::submitButton('<span class="fa  fa-bar-chart-o"></span> Сформировать', ['name' => 'report','value' => 'report','class' => 'btn btn-primary']) ?>
        <?= Html::submitButton('<span class="fa  fa-file-excel-o"></span> Скачать', ['name' => 'export','value' => 'export','class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
$(document).ready(function(){
    $('.btn').click(function(){
        $(".work-report-form").fadeTo(0, 0.3);
        $("#loader").show("slow");
        return true;
    });
});
JS;

$this->registerJs($js);
?>
