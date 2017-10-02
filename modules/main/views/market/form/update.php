<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Form */

$this->title = 'Обновление анкеты';
$this->params['breadcrumbs'][] = ['label' => 'Анкеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];

?>
<div class="form-update">

    <h1>Обновление анкеты</h1>

    <?= $this->render('_form', [
        'model' => $model,
        'statsel' => $statsel,
        'worksel' => $worksel,
    ]) ?>

</div>
