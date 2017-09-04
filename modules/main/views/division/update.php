<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\main\models\Division */

$this->title = 'Обновление записи: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Наши юрлица', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление записи';
?>
<div class="division-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
