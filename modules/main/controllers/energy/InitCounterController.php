<?php

namespace app\modules\main\controllers\energy;
use Yii;
use yii\web\Controller;
use app\modules\main\models\EnergyLog;
use app\modules\main\models\Renter;
use app\models\BaseModel;
use app\controllers\HelpController;

class InitCounterController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['energy']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new EnergyLog();
        $renters = $this->GetActiveRenters();
        $select = array();
        $month = HelpController::GetMonths();

        $smonth = date("m");
        $year = date('Y');
        if(strlen($smonth)==1)
            $smonth.='0';
        foreach($renters as $renter) {
            $select[$renter['id']] = $renter['title'].' ('.$renter['area'].')'; //массив для заполнения данных в select формы
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //удаляем, если имеется запись за текущий месяц, чтобы не было дублей
            EnergyLog::deleteAll(['renter_id'=>$model->renter_id,'year'=>$model->year,'month'=>$model->month]);
            $model->price = $model->delta * $model->renter->koeff;
            if($model->isNewRecord)
                $msg = 'Добавлены начальные данные счетчика арендатора <strong>'. $model->renter->title .'</strong>.';
            else
                $msg = 'Начальные данные счетчика арендатора <strong>'. $model->renter->title .'</strong> были обновлены.';
            $model->save();
            BaseModel::AddEventLog('info',$msg);
            $model->encount = '';
            $model->delta = 0;
        }
        return $this->render('index', [
            'model' => $model,
            'renters' => $select,
            'month' => $month,
            'year' => $year,
            'smonth' => $smonth,
        ]);
    }

    //выборка всех действующих арендаторов
    public function GetActiveRenters(){
        return Renter::find()->select(['id','title','area'])->where(['status'=>1])->orderBy('title', SORT_ASC)->asArray()->all();
    }

}
