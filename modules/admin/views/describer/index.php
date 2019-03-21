<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\DescriberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Получатели';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="describer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Новый получатель', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'email:email',
            //'status',
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
                    0 => 'Отключен',
                    1 => 'Включен',
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
                        $active ? 'Включен' : 'Отключен',
                        [
                            'class' => 'label label-' . ($active ? 'success' : 'danger'),
                        ]
                    );
                },
            ],
            'created_at',
            'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'Действия',
                'headerOptions' => ['width' => '70'],
            ]
        ],
    ]); ?>
</div>
