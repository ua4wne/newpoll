<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Затраты ИТ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visit-index">
    <h1 class="text-center">Задайте условия отбора</h1>
    <?php $form = ActiveForm::begin(['id' => 'search-form']); ?>

    <?= $form->field($model, 'year')->textInput(['value'=>$model->year, 'id'=>'year'])?>

    <?= $form->field($model, 'type')->dropDownList(['expense'=>'По статьям расходов','supplier'=>'По поставщикам'],['id'=>'type','options' => [
        'expense' => ['Selected' => true]
    ]]) ?>

    <div class="form-group">
        <?= Html::submitButton('<span class="fa  fa-bar-chart-o"></span> Сформировать', ['id' => 'main-report','name' => 'report','value' => 'report','class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
    <div class="text-header text-center"></div>
<div class="agileinfo-grap">
    <div id="bar-chart"></div>
    <div id="table-data"></div>
</div>

<?php
$js = <<<JS
     $('#main-report').click(function(e){
         e.preventDefault();
         var year = $('#year').val();
         var type = $('#type').val();
         $.ajax({
             url: '/main/it-report/donut',
             type: 'POST',
             data: {'year':year,'type':type},
             success: function(res){
                 //alert("Сервер вернул вот что: " + res);
                $("#bar-chart").empty();
                Morris.Bar({
                  element: 'bar-chart',
                  data: JSON.parse(res),
                  xkey: 'y',
                  ykeys: ['a'],
                  labels: ['Сумма, руб']
                });
                $.ajax({
                     url: '/main/it-report/table',
                     type: 'POST',
                     data: {'year':year,'type':type},
                     success: function(res){
                     //alert("Сервер вернул вот что: " + res);            
                         $("#table-data").html(res);
                     },
                     error: function(xhr, response){
                     alert('Error! '+ xhr.responseText);
                     }
                });
                $(".text-header").html('<h4>Динамика затрат по ИТ за ' +year+' год</h4>');
             },
             error: function(xhr, response){
             alert('Error! '+ xhr.responseText);
             }
     });
 });
JS;

$this->registerJs($js);
?>