<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\main\models\EnergyLog */
/* @var $form ActiveForm */
$this->title = 'Ввод показаний';
$this->params['breadcrumbs'][] = ['label' => 'Счетчики арендаторов', 'url' => ['index']];
?>
<div class="energy-index">
    <h1>Ввод показаний счетчиков</h1>
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'place_id')->dropDownList($place) ?>
        <?= $form->field($model, 'renter_id')->dropDownList($renters) ?>
        <?= $form->field($model, 'year')->textInput(['value'=>$year]) ?>
        <?= $form->field($model, 'month')->dropDownList($month,['options' =>[ $smonth => ['Selected' => true]]]) ?>
        <?= $form->field($model, 'encount') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- energy-index -->

<?php
$js = <<<JS
    $('#energylog-place_id').change(function() {
		// отправляем AJAX запрос
		var selrent=$("#energylog-place_id").val();
		$.ajax({
			type: "POST",
			url: "/main/energy/renters-counter/selrenter",
			dataType: "html",
	        data: {selrent:selrent},
			// success - это обработчик удачного выполнения событий
			success: function(response) {
			//alert("Сервер вернул вот что: " + response);		     						
				document.getElementById('energylog-renter_id').innerHTML = response;
			}		     		 
		});
	});
    
    $('form').on('beforeSubmit', function(){
     var data = $(this).serialize();
     $.ajax({
     url: '/main/energy/renters-counter/create',
     type: 'POST',
     data: data,
     success: function(res){
         //alert("Сервер вернул вот что: " + res);
         alert(res);
         $('#energylog-encount').val('');
     },
     error: function(){
        alert('Error!');
     }
     });
     return false;
     });
JS;
$this->registerJs($js);
?>