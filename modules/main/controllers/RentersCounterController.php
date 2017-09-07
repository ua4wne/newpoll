<?php

namespace app\modules\main\controllers;

use Codeception\Exception\TestRuntimeException;
use Yii;
use yii\web\Controller;
use app\modules\main\models\EnergyLog;
use app\modules\main\models\Renter;

class RentersCounterController extends Controller
{
    const NOT_VAL = 0; //нет значений
    const MORE_VAL = 1; //предыдущее значение больше текущего
    const LESS_VAL = 2; //предыдущее значение меньше текущего

    private $previous; //предыдущее показание счетчика

    public function actionIndex()
    {
        $model = new EnergyLog();
        $renters = Renter::find()->select(['id','title','area'])->orderBy('title', SORT_ASC)->asArray()->all();
        $select = array();
        $month = array('01'=>'Январь','02'=>'Февраль','03'=>'Март','04'=>'Апрель','05'=>'Май','06'=>'Июнь','07'=>'Июль',
            '08'=>'Август','09'=>'Сентябрь','10'=>'Октябрь','11'=>'Ноябрь','12'=>'Декабрь',);

        $smonth = date("m");
        $year = date('Y');
        if(strlen($smonth)==1)
            $smonth.='0';
        foreach($renters as $renter) {
            $select[$renter['id']] = $renter['title'].' ('.$renter['area'].')'; //массив для заполнения данных в select формы
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
           $result = $this->CheckCountVal($model->renter_id,$model->encount,$model->year,$model->month);
           if($result===self::NOT_VAL){
               Yii::$app->session->setFlash('error', 'Отсутствует показание счетчика за предыдущий месяц!');
           }
           elseif($result===self::MORE_VAL){
               Yii::$app->session->setFlash('error', 'Предыдущее показание счетчика больше, чем текущее!');
           }
           else{
               //удаляем, если имеется запись за текущий месяц, чтобы не было дублей
               EnergyLog::deleteAll(['renter_id'=>$model->renter_id,'year'=>$model->year,'month'=>$model->month]);
               $model->delta = $this->previous - $model->encount;
               $model->price = $model->delta * $model->renter->koeff;
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
        $this->previous = EnergyLog::find()->where(['renter_id'=>$id,'year'=>$y,'month'=>$m])->count();
        if($this->previous) {
            $prev = EnergyLog::find()->select('encount')->where(['renter_id'=>$id,'year'=>$y,'month'=>$m])->limit(1)->all();
            if($prev > $val)
                return self::MORE_VAL;
            else
                return self::LESS_VAL;
        }
            else return self::NOT_VAL;
    }

}
