<?php

namespace app\modules\main\controllers;

use Yii;
use yii\web\Controller;
use app\modules\main\models\EnergyLog;

class RentersCounterController extends Controller
{
    public function actionIndex()
    {
        $model = new EnergyLog();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return 'Load'; //$this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

}
