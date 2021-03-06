<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Form */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Анкеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($this->title)];
?>
    <div class="container">
        <div class="form-form">

            <?php $form = ActiveForm::begin(['id' => 'fMedia']); ?>

            <?= $content; ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

<?php
$js = <<<JS
    $('form').on('beforeSubmit', function(){
        var err = 0;
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
        if(!err){
            var fData = $("form[id='fMedia']").serialize();
            $.ajax({
                url: '/main/market/form/media',
                type: 'POST',
                data: fData,
                success: function(res){
                    //alert("Сервер вернул вот что: " + res);
                    if(res=='OK'){
                        alert("Данные успешно добавлены!");
                        $('#kolvo').val('');
                    }
                },
                error: function(){
                    alert('Error!');
                }
            });
        }
        return false;
    });
JS;

$this->registerJs($js);
?>