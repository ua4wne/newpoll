<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\user\models\PasswordResetRequestForm */
$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login_form row">
    <h3><?= Html::encode($this->title) ?></h3>

    <p>Введите свой Email для получения инструкции по восстановлению пароля</p>



            <?php $form = ActiveForm::begin(['id' => 'password-reset-request-form','options' => [
                'class' => 'form-horizontal',
            ]]); ?>
            <?= $form->field($model, 'email') ?>
            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'reset-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>

</div>