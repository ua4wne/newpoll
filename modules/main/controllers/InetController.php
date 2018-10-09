<?php

namespace app\modules\main\controllers;

use app\modules\main\models\Inet;
use app\modules\main\models\InetSearch;
use app\modules\main\models\Renter;
use yii\web\Controller;
use Yii;
use app\models\BaseModel;
use \yii\web\HttpException;

class InetController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if(Yii::$app->user->can('admin')) {
            $searchModel = new InetSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            throw new HttpException(500 ,'Доступ запрещен');
        }
    }

    public function actionCreate()
    {
        if(Yii::$app->user->can('admin')) {
            $model = new Inet();
            $model->created_at = date('Y-m-d H:i:s');
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $msg = 'Пользователем <strong>' . Yii::$app->user->identity->fname . ' ' . Yii::$app->user->identity->lname . '</strong> добавлена запись о новом подключении интернет <strong>' . $model->renter->title . '</strong>.';
                BaseModel::AddEventLog('info', $msg);
                return $this->redirect(['inet']);
            } else {
                //return print_r($model->errors);
                $statsel = array('dynamic' => 'Динамический IP','static' => 'Выделенный IP');
                $renters = Renter::find()->select(['id', 'title', 'area'])->asArray()->all();
                $rentsel = array();
                foreach ($renters as $renter) {
                    $rentsel[$renter['id']] = $renter['title'].' ('.$renter['area'].')'; //массив для заполнения данных в select формы
                }
                return $this->render('create', [
                    'model' => $model,
                    'rentsel' => $rentsel,
                    'statsel' => $statsel,
                ]);
            }
        }
        else{
            throw new HttpException(500 ,'Доступ запрещен');
        }
    }

}
