<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RenterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Арендаторы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renter-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Новый арендатор', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'area',
            'agent',
            'phone1',
            // 'phone2',
             'encounter',
             'koeff',
            // 'place_id',
            // 'status',
            // 'division_id',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
