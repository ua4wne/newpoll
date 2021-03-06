<?php

namespace app\modules\main\controllers\control;

use app\models\Report;
use app\modules\main\models\Renter;
use Yii;
use yii\web\Controller;
use app\modules\main\models\WorkReport;

class WorkReportController extends Controller
{
    private $firm='';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manager']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new WorkReport();
        if (Yii::$app->request->post('report')){
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $content = $this->GetReport($model->start,$model->finish,$model->renter_id);
                return $this->render('report',[
                    'content' => $content,
                    'start' => $model->start,
                    'finish' => $model->finish,
                    'firm' => $this->firm,
                ]);
            }
        }
        elseif (Yii::$app->request->post('export')) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                Report::RenterReport($model->renter_id,$model->start,$model->finish);
            }
        }
        else{
            $model->start = date('Y-m').'-01';
            $model->finish  = date('Y-m-d');
            $renters = $model->GetActiveRenters();
            $select = array();
            foreach($renters as $renter) {
                $select[$renter['id']] = $renter['name'].' ('.$renter['area'].')'; //массив для заполнения данных в select формы
            }
            $places = $model->GetPlaces();
            $select2 = array();
            foreach($places as $place) {
                $select2[$place['id']] = $place['name']; //массив для заполнения данных в select формы
            }
            return $this->render('index',[
                'model' => $model,
                'renters' => $select,
                'places' => $select2,
            ]);
        }

    }

    public function actionSelected(){ //возврат набора renter для выбранных площадок
        if(\Yii::$app->request->isAjax){
            $places = Yii::$app->request->post('place');
            $res = '';
            foreach ($places as $place){
                $res.=$place.',';
            }
            $res = mb_substr($res,0,strlen($res)-1);
            //выбираем всех арендаторов согласно выбранным площадкам
            $renters =  Renter::find()->select(['id','name','area'])->where(['place_id'=>$places,'status'=>1])->orderBy('name', SORT_ASC)->all();
            $html = '';
            foreach ($renters as $renter){
                $html.='<option value="'.$renter->id.'">'.$renter->name.' ('.$renter->area.')</option>';
            }
            return $html;
        }
        return 'ERR';
    }


    private function GetReport($start,$finish,$renters){
        if(count($renters)==1){
            foreach($renters as $renter){
                $model_renter = Renter::findOne($renter);
            }
            $this->firm = "<p>Компания <b>".$model_renter->title."</b> участок №<b>".$model_renter->area."</b></p>";
            return WorkReport::OneRenterReport($renter,$start,$finish);
        }
        if(count($renters)>1){
            return WorkReport::RentersReport($renters,$start,$finish);
        }
    }

    private function ExportToExcel($start,$finish,$renters){
        if(count($renters)==1){
            return Report::OneRenterExport($renters,$start,$finish);
        }
        if(count($renters)>1){
            return Report::RentersExport($renters,$start,$finish);
        }
    }

}
