<?php

use yii\helpers\Html;
use sjaakp\gcharts\PieChart;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use app\modules\main\models\Questions;
use app\modules\main\models\Logger;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Form */
//$this->registerJsFile('https://www.gstatic.com/charts/loader.js');
$this->title = 'Отчет по анкете';
$this->params['breadcrumbs'][] = ['label' => 'Фильтр', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($this->title)];
?>
<div class="container">
    <h1><?= $form; ?></h1>
    <h4 class="text-center">за период с <?= $model->start; ?> по <?= $model->finish; ?> опрошено человек: <?= $qty ?></h4>

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
        $sum = Logger::find()->select('answer')->where(['question_id' => $question[0]['id']])
            ->andWhere(['between', 'data', "$start", "$finish" ])->count();
        $query = new \yii\db\Query();
        $query->select(['answer', "count(answer) as kol", "count(answer)/$sum as percent"])->from('logger')->where(['question_id' => $question[0]['id']])
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
        echo '<i class="fa fa-plus-square-o fa-2x" aria-hidden="true"></i>';
        echo '<div class="other">';
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'id',
                [
                    'attribute' => 'answer',
                    'label' => 'Ответ'
                ],
                [
                    'attribute' => 'kol',
                    'label' => 'Кол-во ответов',
                ],
                [
                    'attribute' => 'percent',
                    'label' => '% ответов',
                    'content'=>function($data){
                        return round($data['percent']*100,2);
                    }
                ],
                //'updated_at',

            ],
        ]);
        echo '</div>';
        echo '</div>
    </div>';
    }
        ?>
</div>

<?php
$js = <<<JS
$(document).ready(function(){
    var c=0;
    $('.other').hide(); //скрыли значения в таблице вывода анкеты
    $('.fa-plus-square-o').click(function() {// показываем таблицу
    if(c==0){
        $(this).removeClass('fa-plus-square-o');
        $(this).addClass('fa-minus-square-o');
        $(this).next().show();
        c=1;
    }
    else {
        $(this).removeClass('fa-minus-square-o');
        $(this).addClass('fa-plus-square-o');
        $(this).next().hide();
        c=0;
    }
    });
    
});
JS;

$this->registerJs($js);
?>