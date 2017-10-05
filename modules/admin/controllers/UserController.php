<?php

namespace app\modules\admin\controllers;

class UserController extends \yii\web\Controller
{
    public $layout = '@app/views/layouts/main.php';
    
    public function actionIndex()
    {
        return $this->render('index');
    }

}
