<?php

namespace app\modules\main\controllers;

use app\models\Events;
use app\modules\main\models\Form;
use Yii;
use yii\web\Controller;
use app\modules\main\models\MainLog;
use yii\data\ActiveDataProvider;
use \yii\web\HttpException;
use app\modules\main\models\Poll;
use app\models\Report;

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
                'view' => '@app/views/error/view.php',
            ],
        ];
    }

    public function actionIndex()
    {
        if(Yii::$app->user->can('manager')){
            $year = date('Y');
            $month = date('m');
            $start = date('Y-m').'-01';
            $finish = date('Y-m-d');
            $this->view->title = 'Информационная панель';
            //строим график для анализа посещений
            if(\Yii::$app->request->isAjax){
                $data = array();
                $date = $year.'-'.$month.'-';
                $query=Yii::$app->db->createCommand("select sum(ucount) as ucount from visit where `data` like '$date%'"); //текущий месяц
                $logs = $query->queryAll();
                if($logs){
                    foreach($logs as $log){
                        $tmp = array();
                        $tmp['y'] = $year.'-'.$month;
                        if($log['ucount'])
                            $tmp['a'] = $log['ucount'];
                        else
                            $tmp['a'] = 0;
                        array_push($data,$tmp);
                    }
                }

                $period = explode('-', date('Y-m-d', strtotime("$finish -1 month"))); // предыдущий месяц
                $y = $period[0];
                $m = $period[1];
                //$d = $period[2];
                $date = $y.'-'.$m.'-';
                $query=Yii::$app->db->createCommand("select sum(ucount) as ucount from visit where `data` like '$date%'");
                $logs = $query->queryAll();
                foreach($logs as $log){
                    $tmp = array();
                    $tmp['y'] = $y.'-'.$m;
                    $tmp['a'] = $log['ucount'];
                    array_push($data,$tmp);
                }
                $period = explode('-', date('Y-m-d', strtotime("$finish -2 month"))); // препредыдущий месяц
                $y = $period[0];
                $m = $period[1];
                //$d = $period[2];
                $date = $y.'-'.$m.'-';
                $query=Yii::$app->db->createCommand("select sum(ucount) as ucount from visit where `data` like '$date%'");
                $logs = $query->queryAll();
                foreach($logs as $log){
                    $tmp = array();
                    $tmp['y'] = $y.'-'.$m;
                    $tmp['a'] = $log['ucount'];
                    array_push($data,$tmp);
                }
                $period = explode('-', date('Y-m-d', strtotime("$finish -1 year"))); //текущий месяц предыдущего года
                $y = $period[0];
                $m = $period[1];
                //$d = $period[2];
                $date = $y.'-'.$m.'-';
                $query=Yii::$app->db->createCommand("select sum(ucount) as ucount from visit where `data` like '$date%'");
                $logs = $query->queryAll();
                //return print_r($logs);
                foreach($logs as $log){
                    $tmp = array();
                    $tmp['y'] = $y.'-'.$m;
                    $tmp['a'] = $log['ucount'];
                    array_push($data,$tmp);
                }
                return json_encode($data);
            }
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
            if(!isset($max))
                $max = 0;
            $visitors.='<tr><td>Максимально за день</td><td>'.$max['val'].'</td></tr>';
            if(count($result)>0)
                $min = (min($result));
            if(!isset($min))
                $min = 0;
            $visitors.='<tr><td>Минимально за день</td><td>'.$min['val'].'</td></tr>';
            $visitors.='</table>';
            $worktime = '<table class="table table-hover table-striped"><tr  class="tblh"><th>Компания</th><th>Часов в день</th></tr>';
            $time_avg = 0;
            $query="SELECT renter.title, renter.area, Sum(period1)+Sum(period2)+Sum(period3)+Sum(period4)+Sum(period5)+Sum(period6)+Sum(period7)+Sum(period8)+Sum(period9)+Sum(period10)+Sum(period11) AS alltime,
                count(rent_log.data) AS alldata FROM rent_log INNER JOIN renter ON renter.id = rent_log.renter_id
                WHERE rent_log.`data` between '$start' AND '$finish'
                GROUP BY renter.title, renter.area ORDER BY alltime desc";
            $result = $connection->createCommand($query)->queryAll();
            if(count($result))
                $res = $result[0]; //это максимум
            if(!isset($res))
                $res = 1;
            if($res['alldata']>0)
                $hours = $res['alltime']/$res['alldata'];
            if(isset($hours))
                $hours = round($hours,2);
            else
                $hours = 0;
            if($hours>=9)
                $worktime.='<tr><td>'.$res['title'].'</td><td class="success">'.$hours.'</td></tr>';
            else
                $worktime.='<tr><td>'.$res['title'].'</td><td class="danger">'.$hours.'</td></tr>';
            $i = count($result)-1; //это минимум
            if(isset($result[$i]))
                $res = $result[$i];
            else
                $res = 1;
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
            //определяем данные по кол-ву потребителей по территориям
            $rent_count = 0;
            $place_count = '<table class="table table-hover table-striped"><tr  class="tblh"><th>Территория</th><th>Число счетчиков</th></tr>';
            $query = "select p.name, count(r.title) as rents from renter r join place p on p.id=r.place_id where status=1 group by p.name";
            $rows = $connection->createCommand($query)->queryAll();
            foreach ($rows as $row){
                $place_count.='<tr><td>'.$row['name'].'</td><td>'.$row['rents'].'</td></tr>';
                $rent_count += $row['rents'];
            }
            $place_count .= '</table>';
            $events = Events::find()->where(['=','is_read',0])->count(); //общее число не прочитанных событий
            //Yii::$app->session->setFlash('events', $events);
            return $this->render('index',[
                'energy' => $energy,
                'people' => $people,
                'visitors' => $visitors,
                'worktime' => $worktime,
                'time_avg' => $time_avg,
                'main_count' => $main_count,
                'rent_count' => $rent_count,
                'place_count' => $place_count,
                'events' => $events,
                'sysstate' => $this->SysState(),
            ]);
        }
        elseif(Yii::$app->user->can('poll')){
            $this->layout = '@app/views/layouts/poll.php';
            $model = new Poll();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                return $this->redirect(['/main/poll','id'=>$model->form_id]);
            }
            else{
                $forms = Poll::GetActiveForms();
                //return print_r($forms);
                $selform = array();
                foreach ($forms as $form){
                    $selform[$form->id] = $form->name;
                }
                return $this->render('poll',[
                    'model' => $model,
                    'selform' => $selform
                ]);
            }
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    public function actionTable(){
        if(\Yii::$app->request->isAjax){
            $start = date('Y-m').'-01';
            $finish = date('Y-m-d');
            return Report::VisitTable($start,$finish);
        }
    }

    public function actionEvents(){
        if(Yii::$app->user->can('admin')) {
            $query = Events::find()->where(['=', 'is_read', 0]);
            $dataProvider = new ActiveDataProvider([
                //'format' => 'raw',
                'query' => $query,
                'sort' => ['defaultOrder' => ['id' => SORT_ASC]],
                'pagination' => [
                    'pageSize' => Yii::$app->params['page_size'],
                ],
            ]);
            $events = Events::find()->where(['=', 'is_read', 0])->count(); //общее число не прочитанных событий
            Yii::$app->session->setFlash('events', $events);
            return $this->render('events', [
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    public function actionClearLog(){
        if(Yii::$app->user->can('admin')) {
            if (\Yii::$app->request->isAjax) {
                $query=Yii::$app->db->createCommand("delete from events where type != 'error' and is_read = 0");
                $logs = $query->execute();
                return 'Журнал событий очищен';
            }
        }
    }

    public function actionView($id)
    {
        if(Yii::$app->user->can('admin')){
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    public function actionUpdate($id)
    {
        if(Yii::$app->user->can('admin')) {
            $model = $this->findModel($id);
            $model->is_read = 1;
            $model->save();
            return $this->redirect('/main/default/events');
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    public function actionDelete($id)
    {
        if(Yii::$app->user->can('admin')) {
            $this->findModel($id)->delete();
            return $this->redirect(['/main/default/events']);
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    public function actionAddAdmin() {
        if(Yii::$app->user->can('admin')) {
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
        else{
            throw new HttpException(404 ,'Действие запрещено');
        }
    }

    protected function SysState(){
        //memory stat
        $stat['mem_percent'] = round(shell_exec("free | grep Mem | awk '{print $3/$2 * 100.0}'"),0);
        $mem_result = shell_exec("cat /proc/meminfo | grep MemTotal");
        $stat['mem_total'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
        $mem_result = shell_exec("cat /proc/meminfo | grep MemFree");
        $stat['mem_free'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
        $stat['mem_used'] = $stat['mem_total'] - $stat['mem_free'];
        //hdd stat
        $stat['hdd_free'] = round(disk_free_space("/") / 1024 / 1024 / 1024, 2);
        $stat['hdd_total'] = round(disk_total_space("/") / 1024 / 1024/ 1024, 2);
        $stat['hdd_used'] = $stat['hdd_total'] - $stat['hdd_free'];
        $stat['hdd_percent'] = round(sprintf('%.2f',($stat['hdd_used'] / $stat['hdd_total']) * 100), 0);

        $content=          '<div>
                                <p>
                                    <strong>Занято на диске</strong>
                                    <span class="pull-right text-muted">'.$stat['hdd_percent'].'%</span>
                                </p>
                                <div class="progress progress-striped active">';
        $sys_icon='fa fa-cogs fa-3x';
        if($stat['mem_percent']<50)
            $bar_state_mem='progress-bar progress-bar-info';
        else if($stat['mem_percent']>49&&$stat['hdd_percent']<75) {
            $bar_state_mem = 'progress-bar progress-bar-warning';
            $sys_icon='fa fa-warning fa-3x';
        }
        else {
            $bar_state_mem = 'progress-bar progress-bar-danger';
            $sys_icon='fa fa-warning fa-3x';
        }
        if($stat['hdd_percent']<55)
            $bar_state_hdd='progress-bar progress-bar-info';
        else if($stat['hdd_percent']>54&&$stat['hdd_percent']<85) {
            $bar_state_hdd = 'progress-bar progress-bar-warning';
            $sys_icon='fa fa-warning fa-3x';
        }
        else {
            $bar_state_hdd='progress-bar progress-bar-danger';
            $sys_icon='fa fa-warning fa-3x';
        }
        $content.='
                                    <div class="'.$bar_state_hdd.'" role="progressbar" aria-valuenow="'.$stat['hdd_percent'].'" aria-valuemin="0" aria-valuemax="100" style="width: '.$stat['hdd_percent'].'%">
                                        <span class="sr-only">'.$stat['hdd_percent'].'</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p>
                                    <strong>Занято памяти</strong>
                                    <span class="pull-right text-muted">'.$stat['mem_percent'].'%</span>
                                </p>
                                <div class="progress progress-striped active">
                                    <div class="'.$bar_state_mem.'" role="progressbar" aria-valuenow="'.$stat['mem_percent'].'" aria-valuemin="0" aria-valuemax="100" style="width: '.$stat['mem_percent'].'%">
                                        <span class="sr-only">'.$stat['mem_percent'].'</span>
                                    </div>
                                </div>
                            </div>';

        return $content;
    }

    protected function findModel($id)
    {
        if (($model = Events::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
