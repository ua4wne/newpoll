<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Form */

$this->title = 'Обновление записи';
$this->params['breadcrumbs'][] = ['label' => 'Вопросы', 'url' => ['index','id'=>$model->form_id]];

?>
<div class="form-update">

    <h1>Изменение вопроса</h1>

    <?= $this->render('_form', [
        'model' => $model,
        'qstans' => $qstans,
    ]) ?>

</div>
