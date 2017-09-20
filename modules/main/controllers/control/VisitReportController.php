<?php

namespace app\modules\main\controllers\control;

use Yii;
use app\modules\main\models\Visit;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class VisitReportController extends Controller
{
    public function actionIndex()
    {
        $start = date('Y-m').'-01';
        $now = date('Y-m-d');
        $itog = '';
        //$query = Visit::find()->where(['between', 'data', $start, $now]);

        $model = new Visit();
        if (Yii::$app->request->post('report')){
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $query="select `data`, sum(ucount) from visit where `data` between '$model->start' and '$model->finish' group by `data`";
            }
        }
        elseif (Yii::$app->request->post('export')) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                //Report::RenterReport($model->renter_id,$model->start,$model->finish);
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
