<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Form */

$this->title = 'Новый ответ';
$this->params['breadcrumbs'][] = ['label' => $qname, 'url' => ['index','id'=>$model->question_id]];
$this->params['breadcrumbs'][] = ['label' => 'Новый ответ'];
?>
<div class="form-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'question_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'htmlcode')->dropDownList($htmlsel) ?>
    <?= $form->field($model, 'refbook')->dropDownList($refsel) ?>


    <div class="form-group">
        <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
$(document).ready(function(){
    $("#answerform-refbook").hide();
    $("#answerform-refbook").prev().hide();
    $("#answerform-htmlcode").change(function() {
		if($("#answerform-htmlcode").val()=='tonesel'||$("#answerform-htmlcode").val()=='tmulsel') {
			$("#answerform-refbook").show();
			$("#answerform-refbook").prev().show();
		}
		else {
			$("#answerform-refbook").hide();
			$("#answerform-refbook").prev().hide();
		}	
	});
});
JS;

$this->registerJs($js);
?>
