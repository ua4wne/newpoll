<?php

/* @var $this yii\web\View */

//$this->title = 'My Yii Application';
?>
<div class="row">
    <div class="col-lg-3 col-md-6 headpan">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-users fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?= $people ?> чел.</div>
                        <div>Посещение выставки</div>
                    </div>
                </div>
            </div>
            <div class="panel-body dashbrd">
                <div class="list-group">
                    <?= $visitors; ?>
                </div>
            </div>
            <a href="#" class="a-footer">
                <div class="panel-footer">
                    <span class="pull-left">Подробнее</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-down"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 headpan">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-clock-o fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?= $time_avg; ?> час.</div>
                        <div>Работа домов</div>
                    </div>
                </div>
            </div>
            <div class="panel-body dashbrd">
                <div class="list-group">
                    <?= $worktime; ?>
                </div>
            </div>
            <a href="#" class="a-footer">
                <div class="panel-footer">
                    <span class="pull-left">Подробнее</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-down"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 headpan">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-lightbulb-o fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?= $main_count; ?> кВт</div>
                        <div>Энергопотребление</div>
                    </div>
                </div>
            </div>
            <div class="panel-body dashbrd">
                <div class="list-group">
                    <?= $energy; ?>
                </div>
            </div>
            <a href="#" class="a-footer">
                <div class="panel-footer">
                    <span class="pull-left">Подробнее</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-down"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 headpan">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-flash fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?= $rent_count; ?></div>
                        <div>Потребители</div>
                    </div>
                </div>
            </div>
            <div class="panel-body dashbrd">
                <div class="list-group">
                    <?= $place_count; ?>
                </div>
            </div>
            <a href="#" class="a-footer">
                <div class="panel-footer">
                    <span class="pull-left">Подробнее</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-down"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- /.row -->
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-bar-chart-o fa-fw"></i> График посещений выставки
        <div class="pull-right">
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                    Действия
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li>
                        <a href="/main/default/index">Обновить</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body col-md-8">
        <div id="visitor-chart"></div>
    </div>
    <div class="panel-body col-md-4">
        <div id="visitor-bar"></div>
    </div>
</div>
<?php if(Yii::$app->user->can('admin')) : ?>
    <div class="row">
    <div class="panel-heading">
        <ul class="nav nav-pills" role="tablist">
            <li role="presentation" class="active">
                <a href="/main/default/events">События <span class="badge"><?= $events; ?></span></a>
            </li>
        </ul>
    </div>
    <div class="panel-body col-md-6"><?= $sysstate; ?></div>
</div>
<?php endif; ?>
<?php
$js = <<<JS
$(document).ready(function(){
//var now = new Date();
//var month = now.getMonth().toString();
//var year = now.getFullYear().toString();
//if(month.length < 2)
//    month = '0'+month;
//var date = now.getDate().toString()
//if(date.length < 2)
//    date = '0'+date;
     var start = 'start'; //year+'-'+month+'-01';
     var finish = 'finish'; //year+'-'+month+'-'+date;
     $.ajax({
     url: '/main/control/visit-report',
     type: 'POST',
     data: {'start':start,'finish':finish},
     success: function(res){
     //alert("Сервер вернул вот что: " + res);
      $("#visitor-chart").empty();
     Morris.Line({
          element: 'visitor-chart',
          data: JSON.parse(res),
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Кол-во']
      });
     },
     error: function(){
     alert('Error!');
     }
     });
 $.ajax({
     url: '/main/default',
     type: 'POST',
     data: {'start':start,'finish':finish},
     success: function(res){
     //alert("Сервер вернул вот что: " + res);
      $("#visitor-bar").empty();
     Morris.Bar({
          element: 'visitor-bar',
          data: JSON.parse(res),
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Кол-во']
      });
     },
     error: function(){
     alert('Error!');
     }
     });
 });
JS;

$this->registerJs($js);
?>