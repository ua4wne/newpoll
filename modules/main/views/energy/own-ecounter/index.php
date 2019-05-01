<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\main\models\EnergyLog */
/* @var $form ActiveForm */
$this->title = 'Ввод показаний';
$this->params['breadcrumbs'][] = ['label' => 'Собственные счетчики', 'url' => ['index']];
?>
<div class="energy-index">
    <h1 class="text-center">Ввод показаний собственных счетчиков</h1>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'own_ecounter_id')->dropDownList($selmain) ?>
    <?= $form->field($model, 'year')->textInput(['value'=>$year]) ?>
    <?= $form->field($model, 'month')->dropDownList($month,['options' =>[ $smonth => ['Selected' => true]]]) ?>
    <?= $form->field($model, 'encount') ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- energy-index -->