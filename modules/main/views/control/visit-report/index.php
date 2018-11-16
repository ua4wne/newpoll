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

        <?= $form->field($model, 'group')->dropDownList(['not'=>'Без группировки','byday'=>'По дням недели','byweek'=>'По неделям','bymonth'=>'По месяцам']) ?>

        <div class="form-group">
            <?= Html::submitButton('<span class="fa  fa-bar-chart-o"></span> График', ['id' => 'visit-report','name' => 'report','value' => 'report','class' => 'btn btn-primary']) ?>
            <?= Html::submitButton('<span class="fa  fa-table"></span> Таблица', ['id' => 'vtable','name' => 'vtable','value' => 'vtable','class' => 'btn btn-primary']) ?>
            <?= Html::submitButton('<span class="fa  fa-file-excel-o"></span> Скачать', ['name' => 'export','value' => 'export','class' => 'btn btn-primary']) ?>
            <!-- Раздельная кнопка -->
            <div class="btn-group">
                <button type="button" class="btn btn-primary"><span class="fa  fa-bar-chart-o"></span> Аналитика</button>
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                    <li id="view_year"><a href="#">Текущий год</a></li>
                    <li id="view_all"><a href="#">За все время</a></li>
                </ul>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <div class="text-center"></div>
    <div class="agileinfo-grap">
        <div id="chart_visit"></div>
    </div>

<?php
$js = <<<JS
 $('#visit-report').click(function(e){
     e.preventDefault();
     //var data = $("form").serialize();
     var start = $('#searchform-start').val();
     var finish = $('#searchform-finish').val();
     var group = $('#searchform-group').val();
     var msg = '<h4>Динамика посещений выставки за период с '+start+' по '+finish+'</h4>';
     $.ajax({
     url: '/main/control/visit-report',
     type: 'POST',
     data: {'start':start,'finish':finish,'group':group},
     success: function(res){
     //alert("Сервер вернул вот что: " + res);
      $("#chart_visit").empty();
      if(group=='not'){
          Morris.Line({
          element: 'chart_visit',
          data: JSON.parse(res),
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Кол-во']
        });
      }
      else{
          Morris.Bar({
          element: 'chart_visit',
          data: JSON.parse(res),
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Кол-во']
        });
      }
     
     $(".text-center").html(msg);
     },
     error: function(){
     alert('Error!');
     }
     });
 });
$('#vtable').click(function(e){
     e.preventDefault();
     //var data = $("form").serialize();
     var start = $('#searchform-start').val();
     var finish = $('#searchform-finish').val();
     var msg = '<h4>Таблица посещений выставки за период с '+start+' по '+finish+'</h4>';
     $.ajax({
     url: '/main/control/visit-report/table',
     type: 'POST',
     data: {'start':start,'finish':finish},
     success: function(res){
     //alert("Сервер вернул вот что: " + res);
      $("#chart_visit").html(res);
      $(".text-center").html(msg);
     },
     error: function(){
     alert('Error!');
     }
     });
 });
$('#view_year').click(function(e){
     e.preventDefault();
     $.ajax({
     url: '/main/control/visit-report/analise',
     type: 'POST',
     data: {'action':'year'},
     success: function(res){
     //alert("Сервер вернул вот что: " + res);
       $("#chart_visit").empty();
     Morris.Bar({
          element: 'chart_visit',
          data: JSON.parse(res),
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Кол-во']
      }); 
     $(".text-center").html('<h4>Динамика посещений выставки в течении текущего года</h4>');
     },
     error: function(){
     alert('Error!');
     }
     });
 });
$('#view_all').click(function(e){
     e.preventDefault();
     $.ajax({
     url: '/main/control/visit-report/analise',
     type: 'POST',
     data: {'action':'all'},
     success: function(res){
     //alert("Сервер вернул вот что: " + res);
       $("#chart_visit").empty();
     Morris.Bar({
          element: 'chart_visit',
          data: JSON.parse(res),
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Кол-во']
      });
     $(".text-center").html('<h4>Динамика посещений выставки за все время</h4>');
     },
     error: function(){
     alert('Error!');
     }
     });
 });
JS;

$this->registerJs($js);
?>
