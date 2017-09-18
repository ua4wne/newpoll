<?php

namespace app\modules\main\controllers\control;

use app\modules\main\models\Renter;
use Yii;
use yii\web\Controller;
use app\modules\main\models\WorkReport;

class WorkReportController extends Controller
{
    private $firm='';
    public function actionIndex()
    {
        $model = new WorkReport();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $content = $this->GetReport($model->start,$model-finish, $model->renter_id);
            return $this->render('report',[
                'content' => $content,
                'start' => $model->start,
                'finish' => $model->finish,
                'firm' => $this->firm,
            ]);
        }
        else{
            $model->start = date('Y-m').'-01';
            $model->finish  = date('Y-m-d');
            $renters = $model->GetActiveRenters();
            $select = array();
            foreach($renters as $renter) {
                $select[$renter['id']] = $renter['title'].' ('.$renter['area'].')'; //массив для заполнения данных в select формы
            }
            return $this->render('index',[
                'model' => $model,
                'renters' => $select,
            ]);
        }

    }

    private function GetReport($start,$finish,$renters){
        if(count($renters)==1){
            $model_renter = Renter::find()->select(['title','area'])->where(['id'=>$renters]);
            $this->firm = "<p>Компания <b>".$model_renter->title."</b> участок <b>".$model_renter->area."</b></p>";
        }
        if(count($renters)>1){

        }
    }

}
