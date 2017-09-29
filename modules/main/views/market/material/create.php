<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Material */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Справочник материалов', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
