<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;



/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Form */

$this->title = 'Новая анкета';
$this->params['breadcrumbs'][] = ['label' => 'Анкеты', 'url' => ['index']];
$this->registerCssFile('/css/select2.min.css');
$this->registerJsFile('/js/select2.full.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="form-clone">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="form-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'questions')->dropDownList($questions,[
            'multiple' => true,
            'size' => 20,
            //'style' => 'background:gray;color:#fff;'
            'class'=>'form-control select2',
            'data-placeholder'=>'Выберите вопросы новой анкеты'
        ]) ?>

        <?= $form->field($model, 'answers')->checkbox() ?>

        <div class="form-group">
            <?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>

<?php
$js = <<<JS
$(document).ready(function(){
    $(".select2").select2();
});
JS;

$this->registerJs($js);
?>