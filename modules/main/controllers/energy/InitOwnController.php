<?php

namespace app\modules\main\controllers\energy;

use app\models\BaseModel;
use app\modules\admin\models\OwnEcounter;
use Yii;
use app\modules\main\models\OwnLog;

class InitOwnController extends BaseEcounterController
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
        $model = new OwnLog();
        $counters = $this->GetOwnCounters();
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
            OwnLog::deleteAll(['own_ecounter_id'=>$model->own_ecounter_id,'year'=>$model->year,'month'=>$model->month]);
            $own = OwnEcounter::findOne($model->own_ecounter_id);
            $model->encount = $model->encount * $own->koeff;
            $model->delta = $model->delta * $own->koeff;
            $model->price = $model->delta * $own->tarif;
            if($model->isNewRecord)
                $msg = 'Добавлены начальные данные счетчика <strong>'. $own->name .'</strong> за '. $this->SetMonth($model->month) . ' месяц ' . $model->year . ' года.';
            else
                $msg = 'Начальные данные счетчика <strong>'. $own->name .'</strong> за '. $this->SetMonth($model->month) . ' месяц ' . $model->year . ' года были обновлены.';
            $model->save();
            BaseModel::AddEventLog('info',$msg);
            $model->encount = '';
            $model->delta = 0;
        }
        /*else if ($model->load(Yii::$app->request->post()) && !$model->validate()) {
            foreach ($model->getErrors() as $key => $value) {
                echo $key.': '.$value[0];
            }
            return;
        }*/
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
