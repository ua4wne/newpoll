<?php
use yii\helpers\Html;
use app\components\MyWidget;
?>
<?= Html::encode($message) ?>

<?php //echo MyWidget::widget(['name'=>'Сэмэн']); ?>

<?php MyWidget::begin() ?>
    <h2>Hello my Dolly!</h2>
<?php MyWidget::end() ?>

<?= yii\jui\DatePicker::widget(['name' => 'attributeName']) ?>
