<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Посещение выставки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visit-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="fa fa-hand-paper-o" aria-hidden="true"></i> Новая запись', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-upload" aria-hidden="true"></i> Выгрузить шаблон', ['upload'], ['class' => 'btn btn-primary','id'=>'upload']) ?>
        <?= Html::a('<i class="fa fa-download" aria-hidden="true"></i> Загрузить из шаблона', ['download'], ['class' => 'btn btn-success','id'=>'download']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'data',
            'hours',
            'ucount',
            'created_at',
            // 'updated_at',

            //['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Действия', // заголовок столбца
                'headerOptions' => ['width' => '100'], // ширина столбца
                'template' => '{delete}', // кнопка удаления
            ],
        ],
    ]); ?>
</div>

