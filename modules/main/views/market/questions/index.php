<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Вопросы анкеты';
$this->params['breadcrumbs'][] = ['label' => $anket, 'url' => ['/main/market/form/update','id'=>$form_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-index">

    <h1>Вопросы анкеты "<?= Html::encode($anket) ?>"</h1>

    <p>
        <?= Html::a('Новый вопрос', ['create','id' => $form_id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'created_at',
            'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
