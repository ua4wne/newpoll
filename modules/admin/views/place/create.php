<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \app\modules\admin\models\Place */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Территории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="place-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'ecounter' => $ecounter,
    ]) ?>

</div>
