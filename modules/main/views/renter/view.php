<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \app\modules\main\models\Renter */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Арендаторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renter-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый арендатор', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'name',
            'area',
            'agent',
            'phone1',
            'phone2',
            'encounter',
            'koeff',
            'place.name',
            'status',
            'division.name',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
