<?php

namespace app\modules\main\controllers\energy;

use Yii;
use app\modules\main\models\EnergyForm;
use yii\web\Controller;

class OwnCounterController extends Controller
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
        $model = new EnergyForm();
        if(\Yii::$app->request->isAjax){
            $year = Yii::$app->request->post('year');
            return EnergyForm::OwnCountReport($year);
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
            return EnergyForm::OwnDonuteGraph($year);
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
            return EnergyForm::GetOwnTable($year);
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
