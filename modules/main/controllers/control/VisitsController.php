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
use yii\web\UploadedFile;
use PHPExcel;
//use PHPExcel\IOFactory;

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
        if ($model->load(Yii::$app->request->post())){
            $model->fname = UploadedFile::getInstance($model, 'fname');
            /*if ($model->validate()) {
                $path = Yii::$app->params['pathUploads'] . 'download/';
                $model->fname->saveAs( $path . $model->fname);
            } */
            $file = $model->fname;
            $PHPReader = \PHPExcel_IOFactory::load($file->tempName );
            $sheetData = $PHPReader->getActiveSheet()->toArray(null, true, true, true);
            $result = $model->ReadExcelToBase($sheetData);
            Yii::$app->session->setFlash('success', 'Количество успешно добавленых в базу записей - '.$result);
            return $this->redirect('index');
        }
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

    /**
     * Deletes an existing Form model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Form model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Form the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Visit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
