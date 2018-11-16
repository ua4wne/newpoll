<?php

namespace app\modules\main\controllers\control;

use Yii;
use app\modules\main\models\Visit;
use app\modules\main\models\SearchForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use app\models\Report;

class VisitReportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manager']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        //$month = date("m");
        //$period = explode('-', date('Y-m', strtotime("$year-$month-01 -1 month"))); //определяем предыдущий период
        //$y = $period[0];
        //$m = $period[1];
        //$query = Visit::find()->where(['between', 'data', $start, $now]);

        $model = new SearchForm();
        if(\Yii::$app->request->isAjax){
            $model->start = Yii::$app->request->post('start');
            $model->finish = Yii::$app->request->post('finish');
            $model->group = Yii::$app->request->post('group');
            $data = array();
            if($model->start=='start')
                $model->start = date('Y-m').'-01';
            if($model->finish=='finish')
                $model->finish = date('Y-m-d');
            if($model->group=='byday')
                $query=Yii::$app->db->createCommand("select WEEKDAY(`data`) as data, sum(ucount) as ucount from visit where `data` between '$model->start' and '$model->finish' group by WEEKDAY(`data`)");
            else if($model->group=='byweek')
                $query=Yii::$app->db->createCommand("select date_format(`data`, \"%Y-%v\") as data, sum(ucount) as ucount from visit where `data` between '$model->start' and '$model->finish' group by date_format(`data`, \"%Y-%v\")");
            else if($model->group=='bymonth')
                $query=Yii::$app->db->createCommand("select date_format(`data`, \"%Y-%m\") as data, sum(ucount) as ucount from visit where `data` between '$model->start' and '$model->finish' group by date_format(`data`, \"%Y-%m\")");
            else
                $query=Yii::$app->db->createCommand("select `data`, sum(ucount) as ucount from visit where `data` between '$model->start' and '$model->finish' group by `data`");
            $logs = $query->queryAll();
            if($logs) {
                foreach($logs as $log){
                    $tmp = array();
                    switch ($log['data']) {
                        case 0:
                            $log['data']='ПН';
                            break;
                        case 1:
                            $log['data']='ВТ';
                            break;
                        case 2:
                            $log['data']='СР';
                            break;
                        case 3:
                            $log['data']='ЧТ';
                            break;
                        case 4:
                            $log['data']='ПТ';
                            break;
                        case 5:
                            $log['data']='СБ';
                            break;
                        case 6:
                            $log['data']='ВС';
                            break;
                        default:
                            break;
                    }
                    $tmp['y'] = $log['data'];
                    $tmp['a'] = $log['ucount'];
                    array_push($data,$tmp);
                }
            }
            else{
                $tmp = array();
                $tmp['y'] = $model->start;
                $tmp['a'] = 0;
                $tmp['y'] = $model->finish;
                $tmp['a'] = 0;
                array_push($data,$tmp);
            }

            return json_encode($data);
        }
        if (Yii::$app->request->post('export')) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                Report::VisitReport($model->start,$model->finish);
            }
        }
        else{
            $model->start = date('Y-m').'-01';
            $model->finish  = date('Y-m-d');

            return $this->render('index',[
                'model' => $model,
            ]);
        }
    }

    public function actionTable(){
        $model = new SearchForm();
        if(\Yii::$app->request->isAjax) {
            $model->start = Yii::$app->request->post('start');
            $model->finish = Yii::$app->request->post('finish');
            return Report::VisitTable($model->start,$model->finish);
        }
    }

    public function actionAnalise(){

        if(\Yii::$app->request->isAjax) {
            $data = array();
            $action = Yii::$app->request->post('action');
            if($action=='year'){
                $year = date('Y');
                $query=Yii::$app->db->createCommand("select SUBSTRING(`data`, 6, 2) as `month`, sum(ucount) as ucount from visit where `data` like '$year-%' group by `month`");
                $logs = $query->queryAll();
                foreach($logs as $log){
                    $tmp = array();
                    $tmp['y'] = $year.'-'.$log['month'];
                    $tmp['a'] = $log['ucount'];
                    array_push($data,$tmp);
                }
                return json_encode($data);
            }
            elseif($action=='all'){
                $query=Yii::$app->db->createCommand("select SUBSTRING(`data`, 1, 4) as `year`, sum(ucount) as ucount from visit group by `year`");
                $logs = $query->queryAll();
                foreach($logs as $log){
                    $tmp = array();
                    $tmp['y'] = $log['year'];
                    $tmp['a'] = $log['ucount'];
                    array_push($data,$tmp);
                }
                return json_encode($data);
            }
            else
                return 'Не известный запрос!';
        }
    }

}
