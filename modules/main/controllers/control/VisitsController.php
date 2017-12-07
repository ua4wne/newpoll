<?php

namespace app\modules\main\controllers\control;

use app\modules\main\models\UploadExcel;
use Yii;
use app\modules\main\models\Visit;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\BaseModel;
use app\models\Report;

/**
 * VisitsController implements the CRUD actions for Visit model.
 */
class VisitsController extends Controller
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
     * Lists all Visit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $start = date('Y-m').'-01';
        $now = date('Y-m-d');
        $query = Visit::find()->where(['between', 'data', $start, $now]);
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

    public function actionUpload(){
        Report::VisitTemplate();
    }

    public function actionDownload(){
        $model = new UploadExcel();
        return $this->render('upload', [
            'model' => $model,
        ]);
    }

     /**
     * Creates a new Visit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Visit();
        $session = Yii::$app->session;
        $model->data = $session->get('data');
        if(!isset($model->data))
            $model->data = date('Y-m-d');
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $session->set('data', $model->data);
            $hours = $model->hours;
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');
            // подключение к базе данных
            $connection = \Yii::$app->db;

            foreach($hours as $hour){
                //удаляем старые записи, если есть
                $query="delete from visit where data='$model->data' and hours=$hour";
                $connection->createCommand($query)->execute();
                //теперь можно добавлять новые записи
                $query="insert into visit(data,hours,ucount,created_at,updated_at) values('$model->data','$hour',$model->ucount,'$created_at','$updated_at')";
                $connection->createCommand($query)->execute();
            }
            $insert_id = \Yii::$app->db->getLastInsertID();
            if(isset($insert_id)){
                Yii::$app->session->setFlash('success', 'Записи успешно добавлены!');
                //$msg = 'Добавлены данные по посещению на выставке';
                //BaseModel::AddEventLog('info',$msg);
            }
            else {
                Yii::$app->session->setFlash('error', 'При добавлении записей возникли ошибки!');
                $msg = 'При добавлении данных по посещению на выставке возникли ошибки';
                BaseModel::AddEventLog('info',$msg);
            }
            return $this->redirect(['create']);
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
}
