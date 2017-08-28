<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Ecounter */

$this->title = 'Create Ecounter';
$this->params['breadcrumbs'][] = ['label' => 'Ecounters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ecounter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
