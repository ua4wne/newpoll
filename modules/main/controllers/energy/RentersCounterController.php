<?php

namespace app\modules\main\controllers\energy;

use app\modules\main\controllers\energy\BaseRcounterController;
use app\modules\main\controllers\NotFoundHttpException;
use Codeception\Exception\TestRuntimeException;
use Yii;
use app\models\BaseModel;
use app\modules\main\models\EnergyLog;

class RentersCounterController extends BaseRcounterController
{
    const NOT_VAL = 0; //нет значений
    const MORE_VAL = 1; //предыдущее значение больше текущего
    const LESS_VAL = 2; //предыдущее значение меньше текущего

    private $previous; //предыдущее показание счетчика

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
        $month = $this->GetMonths();

        $smonth = date("m");
        $year = date('Y');
        if(strlen($smonth)==1)
            $smonth.='0';
        foreach($renters as $renter) {
            $select[$renter['id']] = $renter['title'].' ('.$renter['area'].')'; //массив для заполнения данных в select формы
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //$model->encount = $model->encount * $model->ecounter->koeff;
           $result = $this->CheckCountVal($model->renter_id,$model->encount,$model->year,$model->month);
           if($result===self::NOT_VAL){
               Yii::$app->session->setFlash('error', 'Отсутствует показание счетчика за предыдущий месяц!');
               $msg = 'Отсутствует показание счетчика арендатора <strong>'. $model->renter->title .'</strong> за предыдущий месяц!';
               BaseModel::AddEventLog('error',$msg);
           }
           elseif($result===self::MORE_VAL){
               Yii::$app->session->setFlash('error', 'Предыдущее показание счетчика больше, чем текущее! ');
               $msg = 'Предыдущее показание счетчика арендатора <strong>'. $model->renter->title .'</strong> больше, чем текущее!';
               BaseModel::AddEventLog('error',$msg);
           }
           else{
               //удаляем, если имеется запись за текущий месяц, чтобы не было дублей
               EnergyLog::deleteAll(['renter_id'=>$model->renter_id,'year'=>$model->year,'month'=>$model->month]);
               $model->delta = $model->encount - $this->previous;
               $model->price = $model->delta * $model->renter->koeff;
               $msg = 'Данные счетчика арендатора <strong>'. $model->renter->title .'</strong> успешно добавлены.';
               BaseModel::AddEventLog('info',$msg);
               $model->save();
           }
           $model->encount = '';
            return $this->render('index', [
                'model' => $model,
                'renters' => $select,
                'month' => $month,
                'year' => $year,
                'smonth' => $smonth,
            ]);
            //return 'Load'; //$this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('index', [
                'model' => $model,
                'renters' => $select,
                'month' => $month,
                'year' => $year,
                'smonth' => $smonth,
            ]);
        }
    }

    /**
     * Displays a single Ecounter model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = EnergyLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //проверка корректности данных счетчика
    public function CheckCountVal($id,$val,$year,$month){
        $period = explode('-', date('Y-m', strtotime("$year-$month-01 -1 month"))); //определяем предыдущий период
        $y = $period[0];
        $m = $period[1];
        //выбираем данные за предыдущий период
        $numrow = EnergyLog::find()->where(['renter_id'=>$id,'year'=>$y,'month'=>$m])->count();
        if($numrow) {
            $row = EnergyLog::find()->select('encount')->where(['renter_id'=>$id,'year'=>$y,'month'=>$m])->limit(1)->all();
            $this->previous = $row[0][encount];
            if($this->previous > $val)
                return self::MORE_VAL;
            else
                return self::LESS_VAL;
        }
        else return self::NOT_VAL;
    }

}
