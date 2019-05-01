<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\OwnEcounter */

$this->title = 'Новый счетчик';
$this->params['breadcrumbs'][] = ['label' => 'Собственные счетчики', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="own-ecounter-create">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
