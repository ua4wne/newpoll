<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\RentLog */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Присутствие на выставке', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rent-log-create">

    <h1>Ввод данных</h1>

    <?= $this->render('_form', [
        'model' => $model,
        'renters' => $renters,
    ]) ?>

</div>

<?php
$js = <<<JS
$(document).ready(function(){
    $('form').submit(function(e){
        e.preventDefault();
        var fData = $("form").serialize();
        //alert('Click me');
        // отправляем AJAX запрос
		$.ajax({
			type: "POST",
		    url: "/main/control/works/create",
		    //dataType: "json",
	        data: fData,
		    // success - это обработчик удачного выполнения событий
		    success: function(res) {
			    alert("Сервер вернул вот что: " + res);
			    
			},
			error: function(){
                alert('Error!');
            }     		 
     	});
    });
});
JS;
$this->registerJs($js);
?>
