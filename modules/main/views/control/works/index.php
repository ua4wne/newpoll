<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Присутствие на выставке';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rent-log-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div>
        <?= Html::a('Новая запись', ['create'], ['class' => 'btn btn-success']) ?>
        <?php
        Modal::begin([
            'header' => '<h3>Выберите дату</h3>',
            'toggleButton' => ['label' => '<i class="fa fa-upload" aria-hidden="true"></i> Выгрузить шаблон','class'=>'btn btn-primary'],
            //'footer' => 'Низ окна',
        ]);

        $form = ActiveForm::begin([
            'id' => 'template-form',
            //'enableAjaxValidation' => true,
            'action' => ['index']
        ]); ?>

        <?= $form->field($model, 'data')->widget(DatePicker::className(),[
            'name' => 'data',
            'options' => ['placeholder' => 'Ввод даты'],
            'value'=> date("yyyy-MM-dd",strtotime($model->data)),
            'convertFormat' => true,
            'pluginOptions' => [
                'format' => 'yyyy-MM-dd',
                'autoclose'=>true,
                'weekStart'=>1, //неделя начинается с понедельника
                'startDate' => '2015-01-01', //самая ранняя возможная дата
                'todayBtn'=>true, //снизу кнопка "сегодня"
            ]
        ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Создать', ['class' => 'btn btn-success','id'=>'upload']) ?>
    </div>

        <?php ActiveForm::end();

        Modal::end();
        ?>
        <?= Html::a('<i class="fa fa-download" aria-hidden="true"></i> Загрузить из шаблона', ['download'], ['class' => 'btn btn-success','id'=>'download']) ?>
    </div>
    <hr>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'renter.title',
            'data',
            'period1',
            'period2',
             'period3',
             'period4',
             'period5',
             'period6',
             'period7',
             'period8',
             'period9',
             'period10',
             'period11',
             //'created_at',
             //'updated_at',

            //['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Действия', // заголовок столбца
                'headerOptions' => ['width' => '100'], // ширина столбца
                'template' => '{delete}', // кнопка удаления
            ],
        ],
    ]); ?>
</div>

<?php
$js = <<<JS
$('#upload').click(function() {
	return true;
});
JS;

$this->registerJs($js);
?>
