<?php

use yii\helpers\Html;
use sjaakp\gcharts\PieChart;
use yii\data\ActiveDataProvider;
use app\modules\main\models\Questions;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Form */
//$this->registerJsFile('https://www.gstatic.com/charts/loader.js');
$this->title = 'Отчет по анкете';
$this->params['breadcrumbs'][] = ['label' => 'Фильтр', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($this->title)];
?>
<div class="container">
    <h1><?= $form; ?></h1>
    <h4>за период с <?= $model->start; ?> по <?= $model->finish; ?></h4>

    <?php
    $start = $model->start;
    $finish = $model->finish;
    //определяем вопросы анкеты
    $query = "SELECT DISTINCT `name` FROM (`questions` JOIN `logger`  ON((`questions`.`id` = `logger`.`question_id`))) WHERE `questions`.`form_id`=$form_id AND `data` BETWEEN '$model->start' AND '$model->finish' ";
    // подключение к базе данных
    $connection = \Yii::$app->db;
    // Составляем SQL запрос
    $command = $connection->createCommand($query);
    //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
    $rows = $command->queryAll();
    //return print_r($rows);
    foreach ($rows as $row) {
        $question = Questions::find()->where(['form_id'=>$form_id,'name'=>$row['name']])->limit(1)->all();
        $query = new \yii\db\Query();
        $query->select(['answer', "count(answer) as kol"])->from('logger')->where(['question_id' => $question[0]['id']])
            ->andWhere(['between', 'data', "$start", "$finish" ])->groupBy('answer')->orderBy('kol DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        echo '<div class="row">
        <div class="col-md-12">';
        echo PieChart::widget([
            'height' => '400px',
            'dataProvider' => $dataProvider,
            'columns' => [
                'answer:string',
                'kol'
            ],
            'options' => [
                'title' => $row['name']
            ],
        ]);
        echo '</div>
    </div>';
    }
        ?>
</div>

<?php
$js = <<<JS
$(document).ready(function(){
    
});
JS;

$this->registerJs($js);
?>