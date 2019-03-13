<?php

namespace app\modules\main\controllers\energy;

use app\models\Report;
use app\modules\admin\models\Describer;
use app\modules\admin\models\Place;
use app\modules\main\models\EnergyForm;
use app\modules\main\models\WorkReport;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\main\models\EnergyLog;
use \yii\web\Controller;
use app\controllers\HelpController;
use app\models\BaseModel;
use app\modules\main\models\Renter;
use app\modules\main\models\RentLog;
use \yii\web\HttpException;


class BillingController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->user->can('manager')) {
            $year = date('Y');
            $month = date('m');
            $period = explode('-', date('Y-m', strtotime("$year-$month-01 -1 month"))); //определяем предыдущий период
            $y = $period[0];
            $m = HelpController::SetMonth($period[1]);

            $dataProvider = new ActiveDataProvider([
                'query' => EnergyLog::find()->where(['year' => $y, 'month' => $period[1]]),
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

            $delta = EnergyLog::find()->where(['year' => $y, 'month' => $period[1]])->sum('delta');
            $delta = round($delta, 2);
            $price = EnergyLog::find()->where(['year' => $y, 'month' => $period[1]])->sum('price');
            $price = round($price, 2);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'year' => $y,
                'month' => $m,
                'delta' => $delta,
                'price' => $price,
            ]);
        } else {
            throw new HttpException(404, 'Доступ запрещен');
        }
    }

    //отправка почты получателям
    public function actionSendMail()
    {
        if (Yii::$app->user->can('energy')) {
            Report::EnergyReport(true); //создание excel-файла отчета по потреблению арендаторами с сохранением на сервере
            if (file_exists('./download/billing.xlsx')) {
                //определяем наличие активных подписчиков
                $describers = Describer::find()->select('email')->where('status=1')->all();
                $subj = 'Расчеты по оплате электроэнергии';
                //отправляем сообщение пользователю
                $msg = '<html><head><title>Расчеты по оплате электроэнергии</title></head>
                    <body><h3>Расчеты по оплате электроэнергии</h3>
                    <p>Здравствуйте!<br>Во вложении находится файл, содержащий расчеты по оплате электроэнергии за предыдущий месяц.</p>
                    <em style="color:red;">Письмо отправлено автоматически. Отвечать на него не нужно.</em><br>
                    <p style="color:darkblue;">С уважением,<br> Ваш почтовый робот.</p>
                    </body></html>';
                $file = './download/billing.xlsx';
                foreach ($describers as $describer) {
                    if (Report::sendToMail($subj, $msg, $describer->email, $file)) {
                        $log = 'На email <strong>' . $describer->email . '</strong> отправлен расчет по электроэнергии.';
                        BaseModel::AddEventLog('info', $log);
                    } else {
                        $log = 'Возникла ошибка при отправке письма на email <strong>' . $describer->email . '</strong>!';
                        $err = $describer->email . '<br>';
                        BaseModel::AddEventLog('error', $log);
                    }
                }
                if (!isset($err)) {
                    Yii::$app->session->setFlash('success', 'Почта успешно отправлена!');
                } else
                    Yii::$app->session->setFlash('error', 'Возникли ошибки при отправке почты следующим получателям:<br>' . $err);
                return $this->redirect(['index']);
            }
        } else {
            throw new HttpException(404, 'Действие запрещено');
        }
    }

    public function actionCalculate()
    {
        if (Yii::$app->user->can('manager')) {
            $model = new RentLog();
            $year = date('Y');
            $renters = Renter::find()->select(['id', 'title', 'area'])->where(['status' => 1])->orderBy('title', SORT_ASC)->asArray()->all();
            $select = array();
            foreach ($renters as $renter) {
                $select[$renter['id']] = $renter['title'] . ' (' . $renter['area'] . ')'; //массив для заполнения данных в select формы
            }

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if (Yii::$app->request->post('report')) {
                    //return 'Calculate';
                    $content = EnergyForm::ViewCalculate($model->data, $model->renter_id);
                    return $this->render('calculate', [
                        'content' => $content,
                        'year' => $model->data,
                    ]);
                }
                if (Yii::$app->request->post('export')) {
                    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                        Report::CalculateToExcel($model->data, $model->renter_id);
                    }
                }

            } else {
                return $this->render('filter', [
                    'model' => $model,
                    'data' => $year,
                    'renter_id' => $select,
                ]);
            }
        } else {
            throw new HttpException(404, 'Доступ запрещен');
        }
    }

    public function actionReport()
    {
        if (Yii::$app->user->can('manager')) {
            Report::EnergyReport(false); //выгрузка excel-файла отчета по потреблению арендаторами без сохранения на сервере
        } else {
            throw new HttpException(404, 'Действие запрещено');
        }
    }

    public function actionSummary()
    {
        if (Yii::$app->user->can('manager')) {
            $model = new WorkReport();
            $model->start = date('Y-m') . '-01';
            $model->finish = date('Y-m-d');
            $renters = Renter::find()->select(['id', 'title', 'area'])->where(['place_id' => 1, 'status' => 1])->orderBy('title', SORT_ASC)->asArray()->all();
            $select = array();
            foreach ($renters as $renter) {
                $select[$renter['id']] = $renter['title'] . ' (' . $renter['area'] . ')'; //массив для заполнения данных в select формы
            }
            $locations = Place::find()->select(['id', 'name'])->all();
            $locs = array();
            foreach ($locations as $location) {
                $locs[$location['id']] = $location['name']; //массив для заполнения данных в select формы
            }

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if (Yii::$app->request->post('report')) {
                    $content = EnergyForm::SummaryReport($model->start, $model->finish, $model->renter_id);
                    return $this->render('summary', [
                        'content' => $content,
                        'from' => $model->start,
                        'to' => $model->finish
                    ]);
                }
                if (Yii::$app->request->post('export')) {
                    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                        Report::SummaryToExcel($model->start, $model->finish, $model->renter_id);
                    }
                }
                if (Yii::$app->request->post('email')) {
                    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                        $file = Report::SummaryToExcel($model->start, $model->finish, $model->renter_id,true);
                        $err = self::MailReport($file,$model->email);
                        if ($err == 0) {
                            Yii::$app->session->setFlash('success', 'Почта успешно отправлена!');
                        } else
                            Yii::$app->session->setFlash('error', 'Возникли ошибки при отправке почты следующим получателям: ' . $model->email);
                        unlink($file); //удаляем файл
                        return $this->redirect(['summary']);
                    }
                }

            } else {
                return $this->render('sum_filter', [
                    'model' => $model,
                    'renters' => $select,
                    'locs' => $locs,
                ]);
            }
        } else {
            throw new HttpException(404, 'Доступ запрещен');
        }
    }

    public function actionGetRenters()
    {
        //$model = new Renter();
        if (\Yii::$app->request->isAjax) {
            $place_id = Yii::$app->request->post('place');
            return Renter::getRenters($place_id);
        }
    }

    //отправка почты получателям
    private function MailReport($file, $email)
    {
        $err = 0;
        if (file_exists($file)) {
            $subj = 'Расчеты по оплате электроэнергии';
            //отправляем сообщение пользователю
            $msg = '<html><head><title>Расчеты по электроэнергии</title></head>
                    <body><h3>Расчеты по электроэнергии</h3>
                    <p>Здравствуйте!<br>Во вложении находится файл, содержащий расчеты по электроэнергии.</p>
                    <em style="color:red;">Письмо отправлено автоматически. Отвечать на него не нужно.</em><br>
                    <p style="color:darkblue;">С уважением,<br> Почтовый робот МС.</p>
                    </body></html>';
            if (Report::sendToMail($subj, $msg, $email, $file)) {
                $log = 'На email <strong>' . $email . '</strong> отправлен расчет по электроэнергии.';
                BaseModel::AddEventLog('info', $log);
            } else {
                $log = 'Возникла ошибка при отправке письма на email <strong>' . $email . '</strong>!';
                BaseModel::AddEventLog('error', $log);
                $err = 1;
            }
        }
        return $err;
    }
}
