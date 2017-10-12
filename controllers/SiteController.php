<?php

namespace app\controllers;

use app\models\Report;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use app\models\BaseModel;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        //Yii::$app->user->id; ID залогиненного пользователя
        if (!\Yii::$app->user->can('adminTask')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $this->view->title = 'Панель управления';
        return $this->render('index');
    }
}
