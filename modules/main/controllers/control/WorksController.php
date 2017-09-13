<?php

namespace app\modules\main\controllers\control;

use Yii;
use app\modules\main\models\RentLog;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WorksController implements the CRUD actions for RentLog model.
 */
class WorksController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all RentLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => RentLog::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new RentLog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RentLog();
        $renters = $model->GetActiveRenters();
        $select = array();
        foreach($renters as $renter) {
            $select[$renter['id']] = $renter['title'].' ('.$renter['area'].')'; //массив для заполнения данных в select формы
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return print_r($model);
            if($model->allrent == true) return 'Флаг все арендаторы установлен';
            else return 'Флаг все арендаторы не установлен';
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'renters' => $select,
            ]);
        }
    }


}
