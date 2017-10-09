<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\main\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Анкетирование-';
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
                    <?//= Html::a('<span class="fa  fa-bar-chart-o"></span> Выбрать', ['/main/poll','id'=>$model->form_id], ['class' => 'btn btn-primary btn-lg']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
    </div>
</div>
