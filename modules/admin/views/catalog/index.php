<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Каталог';
$this->params['breadcrumbs'][] = 'Каталог справочников';
?>
<div class="catalog-index">

    <h1>Каталог справочников</h1>

    <p>
        <?= Html::a('Новая запись', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'nameEN',
            'nameRU',
            'created_at',
            'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'Действия',
                'headerOptions' => ['width' => '70'],
            ]
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
