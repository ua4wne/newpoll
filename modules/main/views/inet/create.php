<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \app\modules\main\models\Renter */

$this->title = 'Новое подключение';
$this->params['breadcrumbs'][] = ['label' => 'Подключения интернет', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renter-create">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'rentsel' => $rentsel,
        'statsel' => $statsel,
    ]) ?>

</div>
