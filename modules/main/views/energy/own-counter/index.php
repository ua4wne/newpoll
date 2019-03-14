<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Собственное потребление';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visit-index">
    <h1 class="text-center">Задайте условия отбора</h1>
    <?php $form = ActiveForm::begin(['id' => 'search-form']); ?>

    <?= $form->field($model, 'year')?>

    <div class="form-group">
        <?= Html::submitButton('<span class="fa  fa-bar-chart-o"></span> Сформировать', ['id' => 'main-report','name' => 'report','value' => 'report','class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<div class="agileinfo-grap">
    <div class="pull-left" style="width: 70%;" id="chart-main"></div>
    <div class="pull-right" style="width: 30%;" id="pie-chart"></div>
    <div id="table-data"></div>
</div>


<?php
$js = <<<JS
 $('#main-report').click(function(e){
     e.preventDefault();
     //var data = $("form").serialize();
     var year = $('#energyform-year').val();
     $.ajax({
     url: '/main/energy/own-counter',
     type: 'POST',
     data: {'year':year},
     success: function(res){
     //alert("Сервер вернул вот что: " + res);
     $("#chart-main").empty();
     $("#pie-chart").empty();
         Morris.Line({
              element: 'chart-main',
              data: JSON.parse(res),
              xkey: 'm',
              ykeys: ['d','p'],
              labels: ['Потребление,кВт.','Стоимость, руб.']
          });
         $.ajax({
         url: '/main/energy/own-counter/donut',
         type: 'POST',
         data: {'year':year},
         success: function(res){
         //alert("Сервер вернул вот что: " + res);            
             Morris.Donut({
              element: 'pie-chart',
              data: JSON.parse(res)
            });
         },
         error: function(){
         alert('Error!');
         }
         });
         $.ajax({
         url: '/main/energy/own-counter/table',
         type: 'POST',
         data: {'year':year},
         success: function(res){
         //alert("Сервер вернул вот что: " + res);            
             $("#table-data").html(res);
         },
         error: function(){
         alert('Error!');
         }
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
