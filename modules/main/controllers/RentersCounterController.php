<?php

namespace app\modules\main\controllers;

use Yii;
use yii\web\Controller;
use app\modules\main\models\EnergyLog;
use app\modules\main\models\Renter;

class RentersCounterController extends Controller
{
    public function actionIndex()
    {
        $model = new EnergyLog();
        $renters = Renter::find()->select(['id','title','area'])->orderBy('title', SORT_ASC)->asArray()->all();
        $select = array();
        $month = array('01'=>'Январь','02'=>'Февраль','03'=>'Март','04'=>'Апрель','05'=>'Май','06'=>'Июнь','07'=>'Июль',
            '08'=>'Август','09'=>'Сентябрь','10'=>'Октябрь','11'=>'Ноябрь','12'=>'Декабрь',);

        $smonth = date("m");
        if(strlen($smonth)==1)
            $smonth.='0';
        foreach($renters as $renter) {
            $select[$renter['id']] = $renter['title'].' ('.$renter['area'].')'; //массив для заполнения данных в select формы
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return 'Load'; //$this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('index', [
                'model' => $model,
                'renters' => $select,
                'month' => $month,
                'year' => date('Y'),
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

}
