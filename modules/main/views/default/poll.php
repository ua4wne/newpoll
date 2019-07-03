<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\main\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Анкетирование';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">

    <div class="row center-block">

            <div class="col-md-offset-3  col-md-6">

                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                <div class="form-group">
                <?= $form->field($model, 'form_id')->dropDownList($selform) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Выбрать', ['class' => 'btn-primary btn-lg', 'name' => 'poll-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
    </div>
</div>

<?php
$js = <<<JS
$(document).ready(function(){

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
            },            
        });
    });
});
JS;

$this->registerJs($js);
?>