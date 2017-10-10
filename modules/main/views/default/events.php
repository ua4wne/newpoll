<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'События системы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="form-group">
        <?= Html::Button('Очистить лог', ['class' => 'btn btn-danger', 'name' => 'clear_log', 'id' => 'clear_log']) ?>
        <?//= Html::a('<span class="fa  fa-bar-chart-o"></span> Выбрать', ['/main/poll','id'=>$model->form_id], ['class' => 'btn btn-primary btn-lg']) ?>
    </div>
    <div class="events">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'id',
                'user.username',
                //'id' => 'ID',
                //'user_id',
                'user_ip',
                'type',
                'msg:html',
                'created_at',
                //'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>

<?php
$js = <<<JS
$(document).ready(function(){
    
    $('#clear_log').click(function(e) {
		e.preventDefault();
		// отправляем AJAX запрос
		$.ajax({
			type: "POST",
		    url: "/main/default/clear-log",
		    //dataType: "json",
	        data: {addvisitor:'set'},
		    // success - это обработчик удачного выполнения событий
		    success: function(res) {
			    //alert("Сервер вернул вот что: " + res);
			    $(".events").text(res);
			}     		 
     	});
	});
});
JS;

$this->registerJs($js);
?>