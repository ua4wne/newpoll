<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Form */

$this->title = 'Новый вопрос';
$this->params['breadcrumbs'][] = ['label' => 'Вопросы', 'url' => ['index','id'=>$model->form_id]];
?>
<div class="form-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
