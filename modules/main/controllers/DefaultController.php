<?php

namespace app\modules\main\controllers;

use yii\web\Controller;

/**
 * Default controller for the `main` module
 */
class DefaultController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {

        $this->view->title = 'Информационная панель';
        return $this->render('index');
    }

    public function actionAddAdmin() {
        $model = User::find()->where(['username' => 'ircut'])->one();
        if (empty($model)) {
            $user = new User();
            $user->username = 'ircut';
            $user->email = 'rogatnev@m-strana.ru';
            $user->fname = 'Администратор';
            $user->lname = 'системы';
            $user->setPassword('$ystemm1');
            $user->generateAuthKey();
            if ($user->save()) {
                echo 'Администратор системы создан';
            }
        }
    }
}
