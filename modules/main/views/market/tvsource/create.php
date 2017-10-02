<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Tvsource */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Справочник медиа', 'url' => ['index']];
?>
<div class="tvsource-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
