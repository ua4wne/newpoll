<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Visit */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Посещение выставки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visit-create">

    <h1>Ввод данных</h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
