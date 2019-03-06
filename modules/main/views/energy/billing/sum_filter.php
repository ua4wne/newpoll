<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model \app\modules\main\models\EnergyLog */
/* @var $form ActiveForm */
$this->title = 'Расчет за период';
$this->params['breadcrumbs'][] = ['label' => 'Расчет потребления за период', 'url' => ['summary']];
?>
<div class="energy-index">
    <h1 class="text-center">Задайте условия отбора</h1>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'start')->widget(DatePicker::className(), [
        'name' => 'start',
        'options' => ['placeholder' => 'Ввод даты'],
        'value' => date("yyyy-MM-dd", strtotime($model->start)),
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'yyyy-MM-dd',
            'autoclose' => true,
            'weekStart' => 1, //неделя начинается с понедельника
            'startDate' => '2015-01-01', //самая ранняя возможная дата
            'todayBtn' => true, //снизу кнопка "сегодня"
        ]
    ]) ?>

    <?= $form->field($model, 'finish')->widget(DatePicker::className(), [
        'name' => 'finish',
        'options' => ['placeholder' => 'Ввод даты'],
        'value' => date("yyyy-MM-dd", strtotime($model->finish)),
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'yyyy-MM-dd',
            'autoclose' => true,
            'weekStart' => 1, //неделя начинается с понедельника
            'startDate' => '2015-01-01', //самая ранняя возможная дата
            'todayBtn' => true, //снизу кнопка "сегодня"
        ]
    ]) ?>

    <?= $form->field($model, 'location')->dropDownList($locs, [
        'id' => 'location'
    ]) ?>

    <?= $form->field($model, 'renter_id')->dropDownList($renters, [
        'id' => 'renters',
        'multiple' => true,
        'size' => 10,
        //'style' => 'background:gray;color:#fff;'
    ]) ?>

    <?= $form->field($model, 'allrent')->checkbox(['id'=>'allrent']) ?>

    <div class="form-group">
        <?= Html::submitButton('<span class="fa  fa-bar-chart-o"></span> Сформировать', ['name' => 'report', 'value' => 'report', 'class' => 'btn btn-primary']) ?>
        <?= Html::submitButton('<span class="fa  fa-file-excel-o"></span> Скачать', ['name' => 'export', 'value' => 'export', 'class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- energy-index -->

<?php
$js = <<<JS
    
     $('#location').change(function(){
         //e.preventDefault();
         $("#allrent").attr('checked', false);
         var selval = this.value;
         $.ajax({
             url: '/main/energy/billing/get-renters',
             type: 'POST',
             data: {'place':selval},
             success: function(res){
             //alert("Сервер вернул вот что: " + res);            
                 $("#renters").html(res);
             },
             error: function(){
                alert('Error!');
             }
         });
     });

    $('#allrent').click(function() {
        if($("#allrent").prop("checked")) {
            $('#renters option').each(function(){
                $(this).prop("selected", true);
            });
        }
        else {
            $("#allrent").prop("disabled", false);
            $('#renters option').each(function(){
                $(this).prop("selected", false);
            });
        }
    });
JS;

$this->registerJs($js);
?>

