<?php

namespace app\modules\main\controllers\energy;

use Yii;
use app\modules\main\models\EnergyForm;
use yii\web\Controller;
use yii\db\Query;

class MainReportController extends Controller
{
    public function actionIndex()
    {
        $model = new EnergyForm();
        if(\Yii::$app->request->isAjax){
            $main = (new Query())->select('id')->from('ecounter')->where(['=', 'name', 'Главный']); //выбираем главный счетчик
            $year = Yii::$app->request->post('year');
            return EnergyForm::CountReport($main,$year);
        }
        else{
            $model->start = date('Y-m').'-01';
            $model->finish  = date('Y-m-d');
            $model->year = date('Y');
            return $this->render('index',[
                'model' => $model,
            ]);
        }
    }

    public function actionDonut()
    {
        $model = new EnergyForm();
        if(\Yii::$app->request->isAjax){
            $year = Yii::$app->request->post('year');
            return EnergyForm::DonuteGraph($year);
        }
        else{
            $model->start = date('Y-m').'-01';
            $model->finish  = date('Y-m-d');
            $model->year = date('Y');
            return $this->render('index',[
                'model' => $model,
            ]);
        }
    }

    public function actionTable()
    {
        $model = new EnergyForm();
        if(\Yii::$app->request->isAjax){
            $year = Yii::$app->request->post('year');
            return EnergyForm::GetTable($year);
        }
        else{
            $model->start = date('Y-m').'-01';
            $model->finish  = date('Y-m-d');
            $model->year = date('Y');
            return $this->render('index',[
                'model' => $model,
            ]);
        }
    }
}
