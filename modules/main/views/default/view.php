<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \app\modules\main\models\Renter */

$this->title = 'Просмотр записи';
$this->params['breadcrumbs'][] = ['label' => 'События', 'url' => ['events']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renter-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Прочтено', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'user_id',
            'user_ip',
            'type',
            'msg',
            'is_read',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
