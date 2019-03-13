<?php

//use yii\helpers\Html;
//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Расчет потребления электроэнергии';
$this->params['breadcrumbs'][] = ['label' => 'Расчет потребления за период', 'url' => ['summary']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="division-index">

    <div class="page-header text-center">
        <h2>Расчет потребления электроэнергии арендаторами</h2>
        <small>За период с <?= $from ?> по <?= $to ?></small>
    </div>

    <?= $content; ?>
</div>
