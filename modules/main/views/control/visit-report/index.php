<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Посещение выставки';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="visit-index">
        <h1>Задайте условия отбора</h1>
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'start')->widget(DateTimePicker::className(),[
            'name' => 'start',
            'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
            'options' => ['placeholder' => 'Ввод даты/времени...'],
            'value'=> date("yyyy-MM-dd",$model->start),
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
            'value'=> date("yyyy-MM-dd",$model->finish),
            'convertFormat' => true,
            'pluginOptions' => [
                'format' => 'yyyy-MM-dd',
                'autoclose'=>true,
                'weekStart'=>1, //неделя начинается с понедельника
                'startDate' => '2015-01-01', //самая ранняя возможная дата
                'todayBtn'=>true, //снизу кнопка "сегодня"
            ]
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('<span class="fa  fa-bar-chart-o"></span> Сформировать', ['name' => 'report','value' => 'report','class' => 'btn btn-primary']) ?>
            <?= Html::submitButton('<span class="fa  fa-file-excel-o"></span> Скачать', ['name' => 'export','value' => 'export','class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <div id="chart_visit">График будет здесь</div>
