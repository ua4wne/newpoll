<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\OwnEcounter */

$this->title = 'Обновление записи: ';
$this->params['breadcrumbs'][] = ['label' => 'Собственные счетчики', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="own-ecounter-update">

    <h1 class="text-center"><?= Html::encode($this->title). $model->name; ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
