<?php

namespace app\modules\main\controllers\control;

class WorksController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
