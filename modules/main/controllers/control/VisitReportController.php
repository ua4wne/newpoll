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
            $data = array();
            if($model->start=='start')
                $model->start = date('Y-m').'-01';
            if($model->finish=='finish')
                $model->finish = date('Y-m-d');
            $query=Yii::$app->db->createCommand("select `data`, sum(ucount) as ucount from visit where `data` between '$model->start' and '$model->finish' group by `data`");
            $logs = $query->queryAll();
            if($logs) {
                foreach($logs as $log){
                    $tmp = array();
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

}
