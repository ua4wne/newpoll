<?php

namespace app\modules\main\controllers\energy;
use app\models\Report;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\main\models\EnergyLog;
use \yii\web\Controller;
use app\controllers\HelpController;


class BillingController extends Controller
{
    public function actionIndex()
    {
        $year = date('Y');
        $month = date('m');
        $period = explode('-', date('Y-m', strtotime("$year-$month-01 -1 month"))); //определяем предыдущий период
        $y = $period[0];
        $m = HelpController::SetMonth($period[1]);

        $dataProvider = new ActiveDataProvider([
            'query' => EnergyLog::find()->where(['year'=>$y,'month'=>$period[1]]),
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
            'sort' => [
                'attributes' => [
                    'area' => SORT_ASC,
                    //'title' => SORT_ASC,
                ]
            ],
        ]);

        $delta = EnergyLog::find()->where(['year'=>$y,'month'=>$period[1]])->sum('delta');
        $price = EnergyLog::find()->where(['year'=>$y,'month'=>$period[1]])->sum('price');

        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'year' => $y,
            'month' => $m,
            'delta' => $delta,
            'price' => $price,
        ]);
    }

    //отправка почты получателям
    public function actionSendMail()
    {
        Report::EnergyReport(true); //создание excel-файла отчета по потреблению арендаторами с сохранением на сервере
    }

    public function actionReport()
    {
        Report::EnergyReport(false); //выгрузка excel-файла отчета по потреблению арендаторами без сохранения на сервере
    }

}
