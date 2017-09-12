<?php

namespace app\modules\main\controllers\energy;
use app\models\Report;
use app\modules\admin\models\Describer;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\main\models\EnergyLog;
use \yii\web\Controller;
use app\controllers\HelpController;
use app\models\BaseModel;


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
        if(file_exists('./download/billing.xlsx')){
            //определяем наличие активных подписчиков
            $describers = Describer::find()->select('email')->where('status=1')->all();
            $subj = 'Расчеты по оплате электроэнергии';
            //отправляем сообщение пользователю
            $msg='<html><head><title>Расчеты по оплате электроэнергии</title></head>
                    <body><h3>Расчеты по оплате электроэнергии</h3>
                    <p>Здравствуйте!<br>Во вложении находится файл, содержащий расчеты по оплате электроэнергии за предыдущий месяц.</p>
                    <em style="color:red;">Письмо отправлено автоматически. Отвечать на него не нужно.</em><br>
                    <p style="color:darkblue;">С уважением,<br> Ваш почтовый робот.</p>
                    </body></html>';
            $file = './download/billing.xlsx';
            foreach($describers as $describer){
                if(Report::sendToMail($subj,$msg,$describer->email,$file)){
                    $log = 'На email <strong>'. $describer->email .'</strong> отправлен расчет по электроэнергии.';
                    BaseModel::AddEventLog('info',$log);
                }
                else{
                    $log = 'Возникла ошибка при отправке письма на email <strong>'. $describer->email .'</strong>!';
                    $err = $describer->email . '<br>';
                    BaseModel::AddEventLog('error',$log);
                }
            }
            if(!isset($err)){
                Yii::$app->session->setFlash('success', 'Почта успешно отправлена!');
            }
            else
                Yii::$app->session->setFlash('error', 'Возникли ошибки при отправке почты следующим получателям:<br>'.$err);
            return $this->redirect(['index']);
        }
    }

    public function actionReport()
    {
        Report::EnergyReport(false); //выгрузка excel-файла отчета по потреблению арендаторами без сохранения на сервере
    }

}
