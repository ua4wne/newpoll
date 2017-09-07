<?php

namespace app\modules\main\controllers;

use Yii;
use yii\web\Controller;
use app\modules\admin\models\Ecounter;
use app\modules\main\models\MainLog;

class InitMainController extends Controller
{
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
            //удаляем, если имеется запись за текущий месяц, чтобы не было дублей
            MainLog::deleteAll(['ecounter_id'=>$model->ecounter_id,'year'=>$model->year,'month'=>$model->month]);
            $model->encount = $model->encount * $model->ecounter->koeff;
            $model->delta = $model->delta * $model->ecounter->koeff;
            $model->price = $model->delta * $model->ecounter->tarif;
            $model->save();
            $model->encount = '';
            $model->delta = 0;
        }
        return $this->render('index',[
                'model' => $model,
                'selmain' => $select,
                'month' => $month,
                'year' => $year,
                'smonth' => $smonth,
            ]
        );

    }

    //выборка всех действующих арендаторов
    public function GetCounters(){
        return Ecounter::find()->select(['id','name','text'])->orderBy('name', SORT_ASC)->asArray()->all();
    }

    //выборка всех месяцев
    public function GetMonths(){
        return array('01'=>'Январь','02'=>'Февраль','03'=>'Март','04'=>'Апрель','05'=>'Май','06'=>'Июнь','07'=>'Июль',
            '08'=>'Август','09'=>'Сентябрь','10'=>'Октябрь','11'=>'Ноябрь','12'=>'Декабрь',);
    }

}
