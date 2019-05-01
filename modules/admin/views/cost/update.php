<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Cost */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Затраты ИТ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление записи';
?>
<div class="cost-update">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sepsel' => $sepsel,
        'unitsel' => $unitsel,
        'expensel' => $expensel,
    ]) ?>

</div>
