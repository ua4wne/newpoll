<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\main\models\EnergyLog */
/* @var $form ActiveForm */
$this->title = 'Ввод показаний';
$this->params['breadcrumbs'][] = ['label' => 'Общие счетчики', 'url' => ['index']];
?>
<div class="energy-index">
    <h1>Ввод начальных показаний</h1>
    <div class="alert alert-warning">Форма заполняется в случае установки нового счетчика.</div>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ecounter_id')->dropDownList($selmain) ?>
    <?= $form->field($model, 'year')->textInput(['value'=>$year]) ?>
    <?= $form->field($model, 'month')->dropDownList($month,['options' =>[ $smonth => ['Selected' => true]]]) ?>
    <?= $form->field($model, 'encount') ?>
    <?= $form->field($model, 'delta')->textInput(['value'=>0]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- energy-index -->
