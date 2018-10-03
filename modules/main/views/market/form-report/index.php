<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\RentLog */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Анкетирование';
//$this->params['breadcrumbs'][] = ['label' => 'Анкеты', 'url' => ['/main/market/form']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($this->title)];
$this->registerCssFile('/css/select2.min.css');
$this->registerJsFile('/js/select2.full.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="form-report-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'start')->widget(DateTimePicker::className(),[
        'name' => 'start',
        'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
        'options' => ['placeholder' => 'Ввод даты/времени...'],
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

    <?= $form->field($model, 'finish')->widget(DateTimePicker::className(),[
        'name' => 'finish',
        'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
        'options' => ['placeholder' => 'Ввод даты/времени...'],
        'value'=> date("yyyy-MM-dd",strtotime($model->finish)),
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'yyyy-MM-dd',
            'autoclose'=>true,
            'weekStart'=>1, //неделя начинается с понедельника
            'startDate' => '2015-01-01', //самая ранняя возможная дата
            'todayBtn'=>true, //снизу кнопка "сегодня"
        ]
    ]) ?>

    <?= $form->field($model, 'form_id')->dropDownList($formselect,[
        'multiple' => true,
        'size' => 20,
        //'style' => 'background:gray;color:#fff;'
        'class'=>'form-control select2',
        'data-placeholder'=>'Выберите анкеты'
    ]) ?>

    <?= $form->field($model, 'version')->dropDownList($verselect) ?>


    <div class="form-group">
        <?= Html::submitButton('Сформировать', ['name' => 'report','value' => 'report','class' => 'btn btn-success']) ?>
        <?= Html::submitButton('<span class="fa  fa-file-excel-o"></span> Скачать', ['name' => 'export','value' => 'export','class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<<JS
$(document).ready(function(){
    $(".select2").select2();
});
JS;

$this->registerJs($js);
?>