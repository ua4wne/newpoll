<?php

namespace app\modules\main\controllers\energy;

use yii\web\Controller;
use app\modules\main\models\Renter;

class BaseRcounterController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
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

    //возвращаем название месяца по номеру
    public function SetMonth($id){
        $months = $this->GetMonths();
        foreach ($months as $key=>$month){
            if($key == $id)
                return mb_strtolower($month,'UTF-8');
        }
    }

}
