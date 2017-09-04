<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \app\modules\main\models\Renter */

$this->title = 'Новый арендатор';
$this->params['breadcrumbs'][] = ['label' => 'Арендаторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'place' => $place,
        'division' => $division,
        'statsel' => $statsel,
    ]) ?>

</div>
