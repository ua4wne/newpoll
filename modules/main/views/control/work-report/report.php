<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Учет времени';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visit-index">

    <div class="page-header text-center">
        <h2>Учет времени присутствия на выставке</h2>
        <small>За период с <?= $start ?> по <?= $finish ?> </small>
    </div>

    <?= $firm ?>

    <?= $content ?>

</div>
