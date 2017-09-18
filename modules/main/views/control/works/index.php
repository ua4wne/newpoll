<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Присутствие на выставке';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rent-log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новая запись', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'renter.title',
            'data',
            'period1',
            'period2',
             'period3',
             'period4',
             'period5',
             'period6',
             'period7',
             'period8',
             'period9',
             'period10',
             'period11',
             //'created_at',
             //'updated_at',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
