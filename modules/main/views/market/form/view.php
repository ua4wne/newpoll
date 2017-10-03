<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Form */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Анкеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($this->title)];
?>
<div class="container">
    <div class="form-form">

        <?php $form = ActiveForm::begin(); ?>

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
        $(".table").find(":input[name*='other']").each(function() {// проверяем каждое поле ввода в форме
			if($(this).prev().is(':checked')){ //если выбран чекбокс
				if(!$(this).val()){// если поле пустое
					alert('Необходимо заполнить поле или выбрать значение из списка!');
					$(this).css('border', '1px solid red');// устанавливаем рамку красного цвета
					$(this).focus(); //установка фокуса на поле с ошибкой
					err=1;
                    return false;
				//	error=1;// определяем индекс ошибки
				}
				else{
					$(this).css('border', '1px solid green');// устанавливаем рамку зеленого цвета
				}
			}
		})
        $('.panel-heading').each(function() { //проверяем наличие вопросов без ответов
            var obj = $(this);
            var qname = obj.find('legend').text();
            var qst = obj.find('input:checked').length;
            //var rqst = obj.find('input[type=radio]:checked').length;
            //alert('val='+qst+' qname='+qname);
            if(qst==0) {
                if(qname!='Ваши контакты?'&&err==0) {
                    alert('Не выбран вариант ответа на вопрос "'+qname+'"');
                    $(':first-child',this).focus();
                    err=1;
                    return false;
                }
            }
        })
    });
});
JS;

$this->registerJs($js);
?>