<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Form */

$this->title = 'Отчет по анкете';
$this->params['breadcrumbs'][] = ['label' => 'Фильтр', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($this->title)];
?>
<div class="container">
    <div class="form-report-form">
        <h1><?= $form; ?></h1>

        <?= $content; ?>

    </div>
</div>

<?php
$js = <<<JS
$(document).ready(function(){
    
});
JS;

$this->registerJs($js);
?>