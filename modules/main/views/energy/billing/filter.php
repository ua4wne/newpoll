<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\main\models\EnergyLog */
/* @var $form ActiveForm */
$this->title = 'Расчет потребления';
$this->params['breadcrumbs'][] = ['label' => 'Расчет потребления', 'url' => ['calculate']];
?>
<div class="energy-index">
    <h1 class="text-center">Укажите год, за который нужно вывести расчет</h1>
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'data')->textInput(['value'=>$data]) ?>
    <?= $form->field($model, 'renter_id')->dropDownList($renter_id,[
        'multiple' => true,
        'size' => 20,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('<span class="fa  fa-bar-chart-o"></span> Сформировать', ['name' => 'report','value' => 'report','class' => 'btn btn-primary']) ?>
        <?= Html::submitButton('<span class="fa  fa-file-excel-o"></span> Скачать', ['name' => 'export','value' => 'export','class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- energy-index -->