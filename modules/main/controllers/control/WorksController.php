<?php

namespace app\modules\main\controllers\control;

use app\models\Report;
use app\modules\main\models\Renter;
use app\modules\main\models\UploadExcel;
use Yii;
use app\modules\main\models\RentLog;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\BaseModel;
use yii\web\UploadedFile;
use PHPExcel;

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
        $model = new UploadExcel();

        //if (Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->data)
                    Report::WorkTemplate($model->data);
                    //return 'OK';
                //} else {
                //    Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
                //    return \yii\widgets\ActiveForm::validate($model);
                //}
            }
        //}
        $start = date('Y-m').'-01';
        $now = date('Y-m-d');
        $model->data = $now;
        $query = RentLog::find()->where(['between', 'data', $start, $now]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
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
                $dbl = RentLog::findOne(['renter_id'=>$model->renter_id,'data'=>$model->data]);
                if(!empty($dbl)) $dbl->delete(); //удаляем дубли, если есть
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

    public function actionDownload(){
        $model = new UploadExcel();
        if ($model->load(Yii::$app->request->post())){
            $model->fname = UploadedFile::getInstance($model, 'fname');
            $file = $model->fname;
            $PHPReader = \PHPExcel_IOFactory::load($file->tempName );
            $sheetData = $PHPReader->getActiveSheet()->toArray(null, true, true, true);
            $result = $model->ReadWorkToBase($sheetData);
            Yii::$app->session->setFlash('success', 'Количество успешно добавленых в базу записей - '.$result);
            return $this->redirect('index');
        }
        return $this->render('upload', [
            'model' => $model,
        ]);
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
        if (($model = RentLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
