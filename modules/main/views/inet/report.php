<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel \app\modules\main\models\RenterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Подключения интернет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <p>Всего подключено участков: <?= $itog ?></p>

    <?= $content ?>
</div>
