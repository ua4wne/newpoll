<?php

namespace app\modules\main\controllers;

use app\modules\main\models\ItReport;
use Yii;
use yii\web\Controller;
use yii\db\Query;

class ItReportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['director']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new ItReport();
        $model->start = date('Y-m') . '-01';
        $model->finish = date('Y-m-d');
        $model->year = date('Y');
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionDonut()
    {
        //$model = new ItReport();
        if (\Yii::$app->request->isAjax) {
            $year = Yii::$app->request->post('year');
            $type = Yii::$app->request->post('type');
            return ItReport::GetGraph($type,$year);
        }
    }

    public function actionTable()
    {
        if (\Yii::$app->request->isAjax) {
            $year = Yii::$app->request->post('year');
            $type = Yii::$app->request->post('type');
            return ItReport::GetTable($type,$year);
        }
    }

}
