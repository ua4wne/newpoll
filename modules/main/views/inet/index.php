<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel \app\modules\main\models\RenterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Подключения интернет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renter-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новая запись', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //    'id',
            'renter.title',
            'connect',
            'disconnect',
            [
                'attribute' => 'ip',
                'format' => 'raw',
                'filter' => [
                    'static' => 'Выделенный IP',
                    'dynamic' => 'Динамический IP',
                ],
                /**
                 * Переопределяем отображение самих данных.
                 * Вместо 1 или 0 выводим Yes или No соответственно.
                 * Попутно оборачиваем результат в span с нужным классом
                 */
                'value' => function ($model, $key, $index, $column) {
                    $active = $model->{$column->attribute} === 'dynamic';
                    return \yii\helpers\Html::tag(
                        'span',
                        $active ? 'Динамический IP' : 'Выделенный IP',
                        [
                            'class' => 'label label-' . ($active ? 'success' : 'primary'),
                        ]
                    );
                },
            ],
            'comment',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
</div>
