<?php

namespace app\modules\main\controllers\control;

use Yii;
use app\modules\main\models\Visit;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $hours = $model->hours;
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');
            // подключение к базе данных
            $connection = \Yii::$app->db;

            foreach($hours as $hour){
                $query="insert into visit(data,hours,ucount,created_at,updated_at) values('$model->data','$hour',$model->ucount,'$created_at','$updated_at')";
                $connection->createCommand($query)->execute();
            }
            $insert_id = \Yii::$app->db->getLastInsertID();
            if(isset($insert_id))
                Yii::$app->session->setFlash('success', 'Записи успешно добавлены!');
            else
                Yii::$app->session->setFlash('error', 'При добавлении записей возникли ошибки!');
            return $this->redirect(['index']);
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
}
