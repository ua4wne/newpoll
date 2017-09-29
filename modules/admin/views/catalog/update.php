<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Catalog */

$this->title = 'Обновление записи';
$this->params['breadcrumbs'][] = ['label' => 'Каталог справочников', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nameRU, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="catalog-update">

    <h1>Обновление записи</h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
