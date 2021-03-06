<?php

namespace app\modules\main\controllers;

use Yii;
use app\modules\main\models\Renter;
use app\modules\main\models\RenterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\main\models\Division;
use app\modules\admin\models\Place;
use app\models\BaseModel;
use \yii\web\HttpException;

/**
 * RenterController implements the CRUD actions for Renter model.
 */
class RenterController extends Controller
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
     * Lists all Renter models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->can('manager')) {
            $searchModel = new RenterSearch();
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

    /**
     * Displays a single Renter model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(Yii::$app->user->can('manager')) {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
        else{
            throw new HttpException(500 ,'Доступ запрещен');
        }
    }

    /**
     * Creates a new Renter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->can('energy')) {
            $model = new Renter();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $msg = 'Пользователем <strong>' . Yii::$app->user->identity->fname . ' ' . Yii::$app->user->identity->lname . '</strong> добавлен новый арендатор <strong>' . $model->title . '</strong>.';
                BaseModel::AddEventLog('info', $msg);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $divisions = Division::find()->select(['id', 'name'])->asArray()->all();
                $places = Place::find()->select(['id', 'name'])->asArray()->all();
                $data = array();
                $data1 = array();
                $statsel = array('1' => 'Действующий', '0' => 'Не действующий');
                foreach ($places as $place) {
                    $data[$place['id']] = $place['name']; //массив для заполнения данных в select формы
                }
                foreach ($divisions as $division) {
                    $data1[$division['id']] = $division['name']; //массив для заполнения данных в select формы
                }
                return $this->render('create', [
                    'model' => $model,
                    'place' => $data,
                    'division' => $data1,
                    'statsel' => $statsel,
                ]);
            }
        }
        else{
            throw new HttpException(500 ,'Доступ запрещен');
        }
    }

    /**
     * Updates an existing Renter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->can('energy')) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $msg = 'Данные арендатора <strong>' . $model->title . '</strong> были обновлены пользователем <strong>' . Yii::$app->user->identity->fname . ' ' . Yii::$app->user->identity->lname . '</strong>.';
                BaseModel::AddEventLog('info', $msg);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $divisions = Division::find()->select(['id', 'name'])->asArray()->all();
                $places = Place::find()->select(['id', 'name'])->asArray()->all();
                $data = array();
                $data1 = array();
                $statsel = array('1' => 'Действующий', '0' => 'Не действующий');
                foreach ($places as $place) {
                    $data[$place['id']] = $place['name']; //массив для заполнения данных в select формы
                }
                foreach ($divisions as $division) {
                    $data1[$division['id']] = $division['name']; //массив для заполнения данных в select формы
                }
                return $this->render('update', [
                    'model' => $model,
                    'place' => $data,
                    'division' => $data1,
                    'statsel' => $statsel,
                ]);
            }
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    /**
     * Deletes an existing Renter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->can('admin')) {
            //$this->findModel($id)->delete();
            $row = Renter::findOne($id);
            $row->delete();
            $msg = 'Данные арендатора <strong>' . $row->title . '</strong> были удалены пользователем <strong>' . Yii::$app->user->identity->fname . ' ' . Yii::$app->user->identity->lname . '</strong>.';
            BaseModel::AddEventLog('info', $msg);

            return $this->redirect(['index']);
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    /**
     * Finds the Renter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return \app\modules\main\models\Renter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Renter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
