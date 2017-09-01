<?php

namespace app\modules\main\controllers;

use Yii;
use app\modules\main\models\Renter;
use app\modules\admin\models\Place;
use app\modules\main\models\Division;
use app\modules\main\models\RenterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        $searchModel = new RenterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Renter model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Renter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Renter();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $places = Place::find()->select(['id','name'])->asArray()->all();
            $divisions = Division::find()->select(['id','name'])->asArray()->all();
            $data = array();
            $data1 = array();
            foreach($places as $place)
            {
                $data[$place['id']] = $place['name']; //массив для заполнения данных в select формы
            }
            foreach($divisions as $division)
            {
                $data1[$division['id']] = $division['name']; //массив для заполнения данных в select формы
            }
            return $this->render('create', [
                'model' => $model,
                'place' => $data,
                'division' => $data1,
            ]);
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $places = Place::find()->select(['id','name'])->asArray()->all();
            $divisions = Division::find()->select(['id','name'])->asArray()->all();
            $data = array();
            $data1 = array();
            foreach($places as $place)
            {
                $data[$place['id']] = $place['name']; //массив для заполнения данных в select формы
            }
            foreach($divisions as $division)
            {
                $data1[$division['id']] = $division['name']; //массив для заполнения данных в select формы
            }
            return $this->render('update', [
                'model' => $model,
                'place' => $data,
                'division' => $data1,
            ]);
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Renter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Renter the loaded model
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
