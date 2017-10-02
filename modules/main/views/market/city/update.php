<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\City */

$this->title = 'Обновление записи ';
$this->params['breadcrumbs'][] = ['label' => 'Справочник городов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="city-update">

    <h1>Обновление записи</h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
