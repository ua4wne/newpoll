<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Ecounter;
use Yii;
use app\modules\admin\models\Place;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\BaseModel;

/**
 * PlaceController implements the CRUD actions for Place model.
 */
class PlaceController extends Controller
{
    public $layout = '@app/views/layouts/main.php';
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
                        'roles' => ['adminTask']
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Place models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Place::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
            'sort' => [
                'attributes' => [
                    'area' => SORT_ASC,
                    //'title' => SORT_ASC,
                ]
            ],
         //   'totalCount' => $query->count('renterenergy')
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Place model.
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
     * Creates a new Place model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Place();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $msg = 'Добавлена новая территория <strong>'. $model->name .'</strong>.';
            BaseModel::AddEventLog('info',$msg);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $ecounters = Ecounter::find()->select(['id','name'])->where(['!=','name','Главный'])->asArray()->all();
            $data = array();
            foreach($ecounters as $ecounter)
            {
                $data[$ecounter['id']] = $ecounter['name']; //массив для заполнения данных в select формы
            }
            return $this->render('create', [
                'model' => $model,
                'ecounter' => $data,
            ]);
        }
    }

    /**
     * Updates an existing Place model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $msg = 'Обновлены данные по территории <strong>'. $model->name .'</strong>.';
            BaseModel::AddEventLog('info',$msg);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $ecounters = Ecounter::find()->select(['id','name'])->where(['!=','name','Главный'])->asArray()->all();
            $data = array();
            foreach($ecounters as $ecounter)
            {
                $data[$ecounter['id']] = $ecounter['name']; //массив для заполнения данных в select формы
            }
            return $this->render('update', [
                'model' => $model,
                'ecounter' => $data,
            ]);
        }
    }

    /**
     * Deletes an existing Place model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        $row = Place::findOne($id);
        $row->delete();
        $msg = 'Территория <strong>'. $row->name .'</strong> была удалена из системы.';
        BaseModel::AddEventLog('info',$msg);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Place model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return \app\modules\main\models\Place the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Place::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
