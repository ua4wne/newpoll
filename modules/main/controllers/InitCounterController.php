<?php

namespace app\modules\main\controllers;
use Yii;
use yii\web\Controller;
use app\modules\main\models\EnergyLog;
use app\modules\main\models\Renter;

class InitCounterController extends Controller
{
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
            //удаляем, если имеется запись за текущий месяц, чтобы не было дублей
            EnergyLog::deleteAll(['renter_id'=>$model->renter_id,'year'=>$model->year,'month'=>$model->month]);
            $model->price = $model->delta * $model->renter->koeff;
            $model->save();
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

    //выборка всех месяцев
    public function GetMonths(){
        return array('01'=>'Январь','02'=>'Февраль','03'=>'Март','04'=>'Апрель','05'=>'Май','06'=>'Июнь','07'=>'Июль',
            '08'=>'Август','09'=>'Сентябрь','10'=>'Октябрь','11'=>'Ноябрь','12'=>'Декабрь',);
    }

}
