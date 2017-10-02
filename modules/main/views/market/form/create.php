<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Form */

$this->title = 'Новая анкета';
$this->params['breadcrumbs'][] = ['label' => 'Анкеты', 'url' => ['index']];
?>
<div class="form-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'statsel' => $statsel,
        'worksel' => $worksel,
    ]) ?>

</div>
