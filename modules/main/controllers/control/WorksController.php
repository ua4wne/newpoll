<?php

namespace app\modules\main\controllers\control;

use Yii;
use app\modules\main\models\RentLog;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\BaseModel;

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
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['guard']
                    ],
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
        $start = date('Y-m').'-01';
        $now = date('Y-m-d');
        $query = RentLog::find()->where(['between', 'data', $start, $now]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_ASC]],
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
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
        //$session = Yii::$app->session;
        //$model->data = $session->get('data');
        //if(!isset($model->data))
            $model->data = date('Y-m-d');
        $renters = $model->GetActiveRenters();
        $select = array();
        foreach ($renters as $renter) {
            $select[$renter['id']] = $renter['title'] . ' (' . $renter['area'] . ')'; //массив для заполнения данных в select формы
        }
        if(\Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post()))
                $model->SaveData();
            return 'OK';
        }
        else {
            return $this->render('create', [
                'model' => $model,
                'renters' => $select,
            ]);
        }
        /*if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //$session->set('data', $model->data);
            $result = $model->SaveData();
            if (isset($result)) {
                Yii::$app->session->setFlash('success', 'Записи успешно добавлены!');
                //$msg = 'Добавлены данные по работе арендаторов на выставке';
                //BaseModel::AddEventLog('info',$msg);

            } else {
                Yii::$app->session->setFlash('error', 'При добавлении записей возникли ошибки!');
                $msg = 'При добавлении данных по работе арендаторов на выставке возникли ошибки';
                BaseModel::AddEventLog('error', $msg);
            }
            return $this->redirect(['create']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'renters' => $select,
            ]);
        }*/
    }
}
