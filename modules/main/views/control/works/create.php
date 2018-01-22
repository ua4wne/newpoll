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
$('form').on('beforeSubmit', function(){
 var data = $(this).serialize();
 $.ajax({
 url: '/main/control/works/create',
 type: 'POST',
 data: data,
 success: function(res){
     //alert("Сервер вернул вот что: " + res);
     if(res=='OK'){
        alert("Данные успешно добавлены!");
        $('#rentlog-period option').each(function(){
            $(this).prop("selected", false);
        });
     }
 },
 error: function(){
    alert('Error!');
 }
 });
 return false;
 });
JS;
$this->registerJs($js);
?>