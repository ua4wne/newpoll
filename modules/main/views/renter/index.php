<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel \app\modules\main\models\RenterSearch */
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

        //    'id',
            'title',
            'area',
            //'agent',
            //'phone1',
            // 'phone2',
             'encounter',
             'koeff',
            // 'place_id',
            'place.name',
            /*[
                'attribute' => 'place_id',
                'filter' => [
                    1 => 'МС Выставка',
                    2 => 'Складской комплекс',
                    3 => 'МС ОП',
                    4 => 'МС ОПГС',
                    5 => 'Парковка большая',
                ],
                'value' => 'place.name',
            ],*/
            // 'status',
            /**
             * Произвольная колонка с определенной логикой отображения и фильтром в виде выпадающего списка
             */
            [
                /**
                 * Название поля модели
                 */
                'attribute' => 'status',
                /**
                 * Формат вывода.
                 * В этом случае мы отображает данные, как передали.
                 * По умолчанию все данные прогоняются через Html::encode()
                 */
                'format' => 'raw',
                /**
                 * Переопределяем отображение фильтра.
                 * Задаем выпадающий список с заданными значениями вместо поля для ввода
                 */
                'filter' => [
                    0 => 'Не действующий',
                    1 => 'Действующий',
                ],
                /**
                 * Переопределяем отображение самих данных.
                 * Вместо 1 или 0 выводим Yes или No соответственно.
                 * Попутно оборачиваем результат в span с нужным классом
                 */
                'value' => function ($model, $key, $index, $column) {
                    $active = $model->{$column->attribute} === 1;
                    return \yii\helpers\Html::tag(
                        'span',
                        $active ? 'Действующий' : 'Не действующий',
                        [
                            'class' => 'label label-' . ($active ? 'success' : 'danger'),
                        ]
                    );
                },
            ],
            // 'division_id',
            'division.name',
            /*[
                'attribute' => 'division_id',
                'value' => 'division.name',
            ],*/
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
