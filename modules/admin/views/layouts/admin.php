<?php

use app\assets\LoginAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

LoginAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!doctype html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= $this->title ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">
                    <?= Html::img( '@web/images/logo.jpg', $options = ['alt'=>'Малоэтажная страна'] ) ?>
                </a>
                <p class="navbar-text">Панель управления</p>
            </div>
        </div>
    </nav>
    <div class="wrap">
        <div class="container">
            <?= $content ?>
        </div>
    </div>


    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>