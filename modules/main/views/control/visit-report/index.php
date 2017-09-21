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
        <?php $form = ActiveForm::begin(['id' => 'search-form']); ?>

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
            <?= Html::submitButton('<span class="fa  fa-bar-chart-o"></span> Сформировать', ['id' => 'visit-report','name' => 'report','value' => 'report','class' => 'btn btn-primary']) ?>
            <?= Html::submitButton('<span class="fa  fa-file-excel-o"></span> Скачать', ['name' => 'export','value' => 'export','class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <div id="chart_visit"></div>

<?php
$js = <<<JS
 $('#visit-report').click(function(e){
     e.preventDefault();
     //var data = $("form").serialize();
     var start = $('#searchform-start').val();
     var finish = $('#searchform-finish').val();
     $.ajax({
     url: '/main/control/visit-report',
     type: 'POST',
     data: {'start':start,'finish':finish},
     success: function(res){
     //alert("Сервер вернул вот что: " + res);
     Morris.Bar({
          element: 'chart_visit',
          data: JSON.parse(res),
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Кол-во']
      });
     },
     error: function(){
     alert('Error!');
     }
     });
 });
JS;

$this->registerJs($js);
?>
