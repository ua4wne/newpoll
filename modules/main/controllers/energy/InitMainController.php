<?php

namespace app\modules\main\controllers\energy;
use app\modules\main\controllers\energy\BaseEcounterController;
use Yii;
use app\modules\main\models\MainLog;
use app\models\BaseModel;

class InitMainController extends BaseEcounterController
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
            if($model->isNewRecord)
                $msg = 'Добавлены начальные данные счетчика <strong>'. $model->ecounter->name .'</strong> за '. $this->SetMonth($model->month) . ' месяц ' . $model->year . ' года.';
            else
                $msg = 'Начальные данные счетчика <strong>'. $model->ecounter->name .'</strong> за '. $this->SetMonth($model->month) . ' месяц ' . $model->year . ' года были обновлены.';
            $model->save();
            BaseModel::AddEventLog('info',$msg);
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

}
