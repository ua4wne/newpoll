<?php

namespace app\modules\main\controllers\control;

use Yii;
use app\modules\main\models\Visit;
use app\modules\main\models\SearchForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class VisitReportController extends Controller
{
    public function actionIndex()
    {
        $start = date('Y-m').'-01';
        $now = date('Y-m-d');
        $month = date("m");
        $year = date('Y');
        $period = explode('-', date('Y-m', strtotime("$year-$month-01 -1 month"))); //определяем предыдущий период
        $y = $period[0];
        $m = $period[1];
        $itog = '';
        //$query = Visit::find()->where(['between', 'data', $start, $now]);

        $model = new SearchForm();
        if(\Yii::$app->request->isAjax){
            $model->start = Yii::$app->request->post('start');
            $model->finish = Yii::$app->request->post('finish');
            $data = array();
            //$tmp = array();
        //    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $query=Yii::$app->db->createCommand("select `data`, sum(ucount) as ucount from visit where `data` between '$model->start' and '$model->finish' group by `data`");
            $logs = $query->queryAll();
            //return print_r($logs);
            foreach($logs as $log){
                $tmp = array();
                $tmp['y'] = $log['data'];
                $tmp['a'] = $log['ucount'];
                array_push($data,$tmp);
            }
            return json_encode($data);
        }
        if (Yii::$app->request->post('report')){
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $query="select `data`, sum(ucount) from visit where `data` between '$model->start' and '$model->finish' group by `data`";
            }
        }
        elseif (Yii::$app->request->post('export')) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                //Report::RenterReport($model->renter_id,$model->start,$model->finish);
                $data = array();
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $query=Yii::$app->db->createCommand("select `data`, sum(ucount) as ucount from visit where `data` between '$model->start' and '$model->finish' group by `data`");
                $logs = $query->queryAll();
                //return print_r($logs);
                foreach($logs as $log){
                    $data[$log['data']] = $log['ucount'];
                }
                return $data;
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

}
