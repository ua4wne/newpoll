<?php

namespace app\modules\main\controllers\energy;

use app\modules\main\controllers\energy\BaseEcounterController;
use Yii;
use app\modules\main\models\MainLog;
use app\models\BaseModel;

class MainCounterController extends BaseEcounterController
{
    const NOT_VAL = 0; //нет значений
    const MORE_VAL = 1; //предыдущее значение больше текущего
    const LESS_VAL = 2; //предыдущее значение меньше текущего

    private $previous; //предыдущее показание счетчика

    public function actionIndex()
    {
        $model = new MainLog();
        $counters = $this->GetCounters();
        $select = array();
        $month = $this->GetMonths();

        $smonth = date("m");
        $year = date('Y');
        if(strlen($smonth)==1)
            $smonth.='0';
        foreach($counters as $count) {
            $select[$count['id']] = $count['name'].' ('.$count['text'].')'; //массив для заполнения данных в select формы
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->encount = $model->encount * $model->ecounter->koeff;
            $result = $this->CheckCountVal($model->ecounter_id,$model->encount,$model->year,$model->month);
            if($result===self::NOT_VAL){
                Yii::$app->session->setFlash('error', 'Отсутствует показание счетчика за предыдущий месяц!');
                $msg = 'Отсутствует показание счетчика <strong>'. $model->ecounter->name .'</strong> за предыдущий месяц!';
                BaseModel::AddEventLog('error',$msg);
            }
            elseif($result===self::MORE_VAL){
                Yii::$app->session->setFlash('error', 'Предыдущее показание счетчика больше, чем текущее! ');
                $msg = 'Предыдущее показание счетчика <strong>'. $model->ecounter->name .'</strong> больше, чем текущее!';
                BaseModel::AddEventLog('error',$msg);
            }
            else{
                //удаляем, если имеется запись за текущий месяц, чтобы не было дублей
                MainLog::deleteAll(['ecounter_id'=>$model->ecounter_id,'year'=>$model->year,'month'=>$model->month]);
                $model->delta = $model->encount - $this->previous;
                $model->price = $model->delta * $model->ecounter->tarif;
                $msg = 'Данные счетчика <strong>'. $model->ecounter->name .'</strong> успешно добавлены.';
                BaseModel::AddEventLog('info',$msg);
                $model->save();
            }
            $model->encount = '';
            $model->delta = 0;
        }

        return $this->render('index',[
            'model' => $model,
            'selmain' => $select,
            'month' => $month,
            'year' => $year,
            'smonth' => $smonth,
        ]);
    }

    //проверка корректности данных счетчика
    public function CheckCountVal($id,$val,$year,$month){
        $period = explode('-', date('Y-m', strtotime("$year-$month-01 -1 month"))); //определяем предыдущий период
        $y = $period[0];
        $m = $period[1];
        //выбираем данные за предыдущий период
        $numrow = MainLog::find()->where(['ecounter_id'=>$id,'year'=>$y,'month'=>$m])->count();
        if($numrow) {
            $row = MainLog::find()->where(['ecounter_id'=>$id,'year'=>$y,'month'=>$m])->limit(1)->asArray()->all();
            $this->previous = $row[0][encount];
            if($this->previous > $val)
                return self::MORE_VAL;
            else
                return self::LESS_VAL;
        }
        else return self::NOT_VAL;
    }

}
