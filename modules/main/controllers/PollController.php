<?php

namespace app\modules\main\controllers;
use app\modules\main\models\Poll;
use Yii;

class PollController extends \yii\web\Controller
{
    public function actionIndex($id)
    {
        $model = new Poll();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

        }
        else{
            return $this->render('index');
        }
    }

}
