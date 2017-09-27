<?php

namespace app\modules\main\controllers;

use Yii;
use yii\web\Controller;
use app\modules\main\models\MainLog;

/**
 * Default controller for the `main` module
 */
class DefaultController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $year = date('Y');
        $month = date('m');
        $start = date('Y-m').'-01';
        $finish = date('Y-m-d');
        $this->view->title = 'Информационная панель';
        //инфа для виджета энергопотребления
        $period = explode('-', date('Y-m', strtotime("$year-$month-01 -1 month"))); //определяем предыдущий период
        $y = $period[0];
        $m = $period[1];
        $main_count = 0;
        $rows = MainLog::find()->where(['=','year',$y])->andWhere(['=','month',$m])->orderBy('ecounter_id', SORT_ASC)->all();
        $energy = '<table class="table table-hover table-striped"><tr  class="tblh"><th>Счетчик</th><th>Потребление, кВт</th></tr>';
        foreach($rows as $row){
            $energy.='<tr><td>'.$row->ecounter->name.'</td><td>'.$row->delta.'</td></tr>';
            if($row->ecounter->name=='Главный')
                $main_count = $row->delta;
        }
        $energy.='</table>';
        $people = 0; //количество посетителей
        $visitors='<table class="table table-hover table-striped"><tr  class="tblh"><th>Показатель</th><th>Кол-во человек</th></tr>';
        $connection = \Yii::$app->db;
        $query="select sum(ucount) as people from visit where data between '$start' and '$finish'";
        $result = $connection->createCommand($query)->queryAll();
        $people = $result[0]['people'];
        $visitors.='<tr><td>Посетителей всего</td><td>'.$people.'</td></tr>';
        $query="select sum(ucount) as val from visit where data between '$start' and '$finish' group by data";
        $result = $connection->createCommand($query)->queryAll();
        if(count($result)>0)
            $max = (max($result));
        $visitors.='<tr><td>Максимально за день</td><td>'.$max['val'].'</td></tr>';
        if(count($result)>0)
            $min = (min($result));
        $visitors.='<tr><td>Минимально за день</td><td>'.$min['val'].'</td></tr>';
        $visitors.='</table>';
        $worktime = '<table class="table table-hover table-striped"><tr  class="tblh"><th>Компания</th><th>Часов в день</th></tr>';
        $time_avg = 0;
        $query="SELECT renter.title, renter.area, Sum(period1)+Sum(period2)+Sum(period3)+Sum(period4)+Sum(period5)+Sum(period6)+Sum(period7)+Sum(period8)+Sum(period9)+Sum(period10)+Sum(period11) AS alltime,
                count(rent_log.data) AS alldata FROM rent_log INNER JOIN renter ON renter.id = rent_log.renter_id
                WHERE rent_log.`data` between '$start' AND '$finish'
                GROUP BY renter.title, renter.area ORDER BY alltime desc";
        $result = $connection->createCommand($query)->queryAll();
        $res = $result[0]; //это максимум
        if($res['alldata']>0)
            $hours = $res['alltime']/$res['alldata'];
        $hours = round($hours,2);
        if($hours>=9)
            $worktime.='<tr><td>'.$res['title'].'</td><td class="success">'.$hours.'</td></tr>';
        else
            $worktime.='<tr><td>'.$res['title'].'</td><td class="danger">'.$hours.'</td></tr>';
        $i = count($result)-1; //это минимум
        $res = $result[$i];
        if($res['alldata']>0)
            $hours = $res['alltime']/$res['alldata'];
        if($hours>=9)
            $worktime.='<tr><td>'.$res['title'].'</td><td class="success">'.$hours.'</td></tr>';
        else
            $worktime.='<tr><td>'.$res['title'].'</td><td class="danger">'.$hours.'</td></tr>';
        //считаем среднее время работы домов
        $time = 0;
        $data = 0;
        foreach ($result as $res){
            $time = $time + $res['alltime'];
            $data = $data + $res['alldata'];
        }
        if($data>0)
            $hours = $time/$data;
        $time_avg = round($hours,2);
        if($time_avg>=9)
            $worktime.='<tr><td>В среднем</td><td class="success">'.$time_avg.'</td></tr>';
        else
            $worktime.='<tr><td>В среднем</td><td class="danger">'.$time_avg.'</td></tr>';

        $worktime.='</table>';
        return $this->render('index',[
            'energy' => $energy,
            'people' => $people,
            'visitors' => $visitors,
            'worktime' => $worktime,
            'time_avg' => $time_avg,
            'main_count' => $main_count,
        ]);
    }

    public function actionAddAdmin() {
        $model = User::find()->where(['username' => 'ircut'])->one();
        if (empty($model)) {
            $user = new User();
            $user->username = 'ircut';
            $user->email = 'rogatnev@m-strana.ru';
            $user->fname = 'Администратор';
            $user->lname = 'системы';
            $user->setPassword('$ystemm1');
            $user->generateAuthKey();
            if ($user->save()) {
                echo 'Администратор системы создан';
            }
        }
    }
}
