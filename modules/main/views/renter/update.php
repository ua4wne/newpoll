<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\main\models\Renter */

$this->title = 'Обновление записи: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Арендаторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление записи';
?>
<div class="renter-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'place' => $place,
        'division' => $division,
        'statsel' => $statsel,
    ]) ?>

</div>
