<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \app\modules\main\models\Division */

$this->title = 'Новое юрлицо';
$this->params['breadcrumbs'][] = ['label' => 'Наши юрлица', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="division-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
