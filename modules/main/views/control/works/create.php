<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\RentLog */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Присутствие на выставке', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rent-log-create">

    <h1>Ввод данных</h1>

    <?= $this->render('_form', [
        'model' => $model,
        'renters' => $renters,
    ]) ?>

</div>
