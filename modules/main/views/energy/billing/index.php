<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Расчет потребления электроэнергии';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="division-index">

    <div class="page-header text-center">
        <h2>Расчет потребления электроэнергии арендаторами</h2>
        <small>За <?= $month ?> месяц <?= $year ?> года</small>
    </div>

    <p class="text-info">ИТОГО: <?= $delta ?> кВт. На сумму в размере <?= $price ?> рублей</p>

    <p>
        <?= Html::a('<span class="fa fa-facebook"></span> Отправить', ['send-mail'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<span class="fa fa-facebook"></span> Скачать', ['report'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //   'id',
            'renter.title',
            'encount',
            'delta',
            'price',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
