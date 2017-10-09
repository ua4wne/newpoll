<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Form */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Html::encode($this->title)];
?>
    <div class="container">
        <div class="form-form">

            <?php $form = ActiveForm::begin(['id' => 'fPoll']); ?>

            <?= $content; ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

<?php
$js = <<<JS
$(document).ready(function(){
    $('form').submit(function(){
        var err = 0;
        //e.preventDefault();
        $(".table").find(":input[name*='other']").each(function() {// проверяем каждое поле ввода в форме
			if($(this).prev().is(':checked')){ //если выбран чекбокс
				if(!$(this).val()){// если поле пустое
					alert('Необходимо заполнить поле или выбрать значение из списка!');
					$(this).css('border', '1px solid red');// устанавливаем рамку красного цвета
					$(this).focus(); //установка фокуса на поле с ошибкой
					err=1;
					return false;
				}
				else{
					$(this).css('border', '');// устанавливаем рамку зеленого цвета
				}
			}
		})
        $('.panel-info').each(function() { //проверяем наличие вопросов без ответов
            var obj = $(this);
            var qname = obj.find('.panel-heading').text();
            var qst = obj.find('input:checked').length;
            //var rqst = obj.find('input[type=radio]:checked').length;
            //alert('val='+qst+' qname='+qname);
            if(qst==0) {
                if(qname!='Ваши контакты?') {
                    err++;
                    alert('Не выбран вариант ответа на вопрос "'+qname+'"');
                    $(':first-child',this).focus();
                    return false;
                }
            }
        })
        
        if(err) return false;
        else return true;
        /*var fData = $("form[id='fPoll']").serialize();
        $.ajax({
            url: '/main/poll?id='+$('#form_id').val(),
            type: 'POST',
            data: fData,
            success: function(res){
                alert("Сервер вернул вот что: " + res);
                if(res=='OK'){
                    alert("Данные успешно добавлены!");
                    $('#kolvo').val('');
                }
            },
            error: function(){
                alert('Error!');
            }
        });*/
    });
        
    $('#visitor').click(function() {
		//e.preventDefault();
		// отправляем AJAX запрос
		$.ajax({
			type: "POST",
		    url: "/main/poll/add-visitor",
		    //dataType: "json",
	        data: {addvisitor:'set'},
		    // success - это обработчик удачного выполнения событий
		    success: function(res) {
			    //alert("Сервер вернул вот что: " + res);
			    $("#visitors").text(res);
			}
     		 
     	});
	});
});
JS;

$this->registerJs($js);
?>