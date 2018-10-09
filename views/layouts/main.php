<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>
<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <p class="navbar-text">Малоэтажная страна</p>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><?= Html::a("<i class=\"fa fa-user fa-fw\"></i> Профиль", '/user/profile/index', [
                                'data' => [
                                    'method' => 'post'
                                ],
                            ]
                        );?>
                    </li>
                    <li class="divider"></li>
                    <li><?= Html::a("<i class=\"fa fa-sign-out\"></i> Выход", '/user/default/logout', [
                                'data' => [
                                    'method' => 'post'
                                ],
                            ]
                        );?>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li>
                        <div class="user-panel">
                            <div class="pull-left image"><img class="img-circle" src="<?= Yii::$app->user->identity->image ?>" alt="image"></div>

                            <div class="pull-left info">
                                <p><?= Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname ?></p>
                                <span class="label label-success">online</span>
                            </div>
                        </div>
                    </li>
                    <?php if(Yii::$app->user->can('manager')) : ?>
                    <li>
                        <a href="/main/default/index"><i class="fa fa-tachometer"></i> Главная</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-file-text-o"></i> Документы<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/main/energy/billing/calculate">Расчет по арендатору</a>
                            </li>
                            <li>
                                <a href="/main/inet/report">Подключения к интернет</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-book fa-fw"></i> Контакты<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="/main/renter">Арендаторы</a></li>
                            <li ><a href="/main/division">Наши юрлица</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="#"><i class="fa fa-users fa-fw"></i> Маркетинг<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <?php if(Yii::$app->user->can('market')) : ?>
                                <li><a href="/main/market/form">Анкеты</a></li>
                            <?php endif; ?>
                            <?php if(Yii::$app->user->can('guard')) : ?>
                                <li><a href="/main/market/form/media">Источники медиарекламы</a></li>
                            <?php endif; ?>
                            <?php if(Yii::$app->user->can('market')) : ?>
                                <li ><a href="#"><i class="fa fa-book fa-fw"></i>Справочники<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="/main/market/city">Справочник городов</a>
                                        </li>
                                        <li>
                                            <a href="/main/market/material">Справочник материалов</a>
                                        </li>
                                        <li>
                                            <a href="/main/market/tvsource">Справочник медиа</a>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-bar-chart fa-fw"></i> Отчеты<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/main/market/form-report">Анкетирование</a>
                            </li>
                            <li>
                                <a href="/main/control/visit-report">Посещаемость выставки</a>
                            </li>
                            <li>
                                <a href="/main/control/work-report">Присутствие на выставке</a>
                            </li>
                            <li>
                                <a href="#">Потребление эл. энергии <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="/main/energy/main-report">Счетчики общие</a>
                                    </li>
                                    <li>
                                        <a href="/main/energy/rent-counter">Счетчики арендаторов</a>
                                    </li>
                                    <li>
                                        <a href="/main/energy/own-counter">Собственное потребление</a>
                                    </li>
                                </ul>
                                <!-- /.nav-third-level -->
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <?php if(Yii::$app->user->can('guard')) : ?>
                        <li>
                            <a href="#"><i class="fa fa-table fa-fw"></i> Посещение<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="/main/control/works">Присутствие на выставке</a>
                                </li>
                                <li>
                                    <a href="/main/control/visits">Посещение выставки</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    <?php endif; ?>
                    <?php if(Yii::$app->user->can('energy')) : ?>
                    <li>
                        <a href="#"><i class="fa fa-lightbulb-o fa-fw"></i> Энергоучет<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/main/energy/main-counter">Счетчики общие</a>
                            </li>
                            <li>
                                <a href="/main/energy/renters-counter">Счетчики арендаторов</a>
                            </li>
                            <li>
                                <a href="#">Начальные показания <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="/main/energy/init-main">Счетчики общие</a>
                                    </li>
                                    <li>
                                        <a href="/main/energy/init-counter">Счетчики арендаторов</a>
                                    </li>
                                </ul>
                                <!-- /.nav-third-level -->
                            </li>
                            <li>
                                <a href="/main/energy/billing">Расчет потребления</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <?php endif; ?>
                    <?php if(Yii::$app->user->can('admin')) : ?>
                    <li>
                        <a href="#"><i class="fa fa-gear fa-fw"></i> Настройки<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/admin/ecounter">Счетчики общие</a>
                            </li>
                            <li>
                                <a href="/admin/place">Территории</a>
                            </li>
                            <li>
                                <a href="/admin/user">Пользователи</a>
                            </li>
                            <li>
                                <a href="/admin/describer">Подписчики</a>
                            </li>
                            <li>
                                <a href="/admin/catalog">Справочники</a>
                            </li>
                            <li>
                                <a href="/main/inet">Подключения к интернет</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">Информационная панель</h3>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php echo Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [], ]); ?>
                <?php if( Yii::$app->session->hasFlash('success') ): ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo Yii::$app->session->getFlash('success'); ?>
                    </div>
                <?php endif;?>
                <?php if( Yii::$app->session->hasFlash('error') ): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo Yii::$app->session->getFlash('error'); ?>
                    </div>
                <?php endif;?>
                <?= $content; ?>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>

