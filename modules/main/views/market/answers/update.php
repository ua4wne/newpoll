<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Form */

$this->title = 'Обновление записи';
$this->params['breadcrumbs'][] = ['label' => 'Ответы', 'url' => ['index','id'=>$model->question_id]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view','id'=>$model->id]];

?>
