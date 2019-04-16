<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Cost */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Затраты ИТ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cost-create">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sepsel' => $sepsel,
        'unitsel' => $unitsel,
        'expensel' => $expensel,
    ]) ?>

</div>

<?php
$js = <<<JS
$(document).ready(function(){
    $("#sepsel").prepend( $('<option value="0">Выберите поставщика</option>') );
    $("#sepsel :first('Выберите поставщика')").attr("selected", "selected"); 
    $("#sepsel :first('Выберите поставщика')").attr("disabled", "disabled");
    
    $("#unitsel").prepend( $('<option value="0">Выберите подразделение</option>') );
    $("#unitsel :first('Выберите подразделение')").attr("selected", "selected"); 
    $("#unitsel :first('Выберите подразделение')").attr("disabled", "disabled");
    
    $("#expensel").prepend( $('<option value="0">Выберите статью расходов</option>') );
    $("#expensel :first('Выберите статью расходов')").attr("selected", "selected"); 
    $("#expensel :first('Выберите статью расходов')").attr("disabled", "disabled");
});
JS;

$this->registerJs($js);
?>