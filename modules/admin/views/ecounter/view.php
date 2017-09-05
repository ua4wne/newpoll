<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \app\modules\admin\models\Ecounter */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Электросчетчики', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ecounter-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый счетчик', ['create'], ['class' => 'btn btn-success']) ?>
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
            'name',
            'text',
            'koeff',
            'tarif',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
