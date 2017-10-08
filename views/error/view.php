<?php
use yii\helpers\Html;

$this->title = 'Ошибка';

$exception = Yii::$app->errorHandler->exception;
if ($exception !== null) {
    $statusCode = $exception->statusCode;
    //$name = $exception->getName();
    $message = $exception->getMessage();
}?>

<div class="content">
    <h2>Ой</h2>
    <?php if($statusCode!=404) : ?>
        <img src="/images/ops.jpg" alt="oops">
    <?php else : ?>
        <img src="/images/smile.png" alt="404">
    <?php endif; ?>
    <h2><?=$message ?></h2>
</div>