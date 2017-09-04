<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \app\modules\admin\models\Ecounter */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Новый счетчик', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ecounter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
