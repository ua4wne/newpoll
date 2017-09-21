<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Потребление общих счетчиков';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visit-index">
    <h1>Задайте условия отбора</h1>
    <?php $form = ActiveForm::begin(['id' => 'search-form']); ?>

    <?= $form->field($model, 'year')?>

    <div class="form-group">
        <?= Html::submitButton('<span class="fa  fa-bar-chart-o"></span> Сформировать', ['id' => 'main-report','name' => 'report','value' => 'report','class' => 'btn btn-primary']) ?>
        <?//= Html::submitButton('<span class="fa  fa-file-excel-o"></span> Скачать', ['name' => 'export','value' => 'export','class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<div id="chart_visit"></div>

<?php
$js = <<<JS
 $('#main-report').click(function(e){
     e.preventDefault();
     //var data = $("form").serialize();
     var year = $('#energyform-year').val();
     $.ajax({
     url: '/main/energy/main-report',
     type: 'POST',
     data: {'year':year},
     success: function(res){
     alert("Сервер вернул вот что: " + res);
     Morris.Bar({
          element: 'chart_visit',
          data: JSON.parse(res),
          xkey: 'm',
          ykeys: ['d'],
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

