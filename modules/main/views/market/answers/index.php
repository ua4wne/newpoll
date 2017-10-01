<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ответы на вопрос';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-index">

    <h1>Вопросы анкеты "<?= Html::encode($anket) ?>"</h1>

    <p>
        <?= Html::a('Новый ответ', ['create','id' => $qst_id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'htmlcode',
            'source',
            'created_at',
            'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>