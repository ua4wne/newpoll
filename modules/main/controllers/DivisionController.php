<?php

namespace app\modules\main\controllers;

use Yii;
use app\modules\main\models\Division;
use app\models\BaseModel;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\HttpException;

/**
 * DivisionController implements the CRUD actions for Division model.
 */
class DivisionController extends Controller
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
     * Lists all Division models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->can('manager')) {
            $dataProvider = new ActiveDataProvider([
                'query' => Division::find(),
                'pagination' => [
                    'pageSize' => Yii::$app->params['page_size'],
                ],
                'sort' => [
                    'attributes' => [
                        'area' => SORT_ASC,
                        //'title' => SORT_ASC,
                    ]
                ],
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    /**
     * Displays a single Division model.
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
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    /**
     * Creates a new Division model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->can('admin')) {
            $model = new Division();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $msg = 'Добавлено новое юрлицо <strong>' . $model->name . '</strong>.';
                BaseModel::AddEventLog('info', $msg);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    /**
     * Updates an existing Division model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->can('admin')) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $msg = 'Данные юрлица <strong>' . $model->name . '</strong> были обновлены.';
                BaseModel::AddEventLog('info', $msg);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    /**
     * Deletes an existing Division model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->can('admin')) {
            $row = Division::findOne($id);
            //   $this->findModel($id)->delete();
            $row->delete();
            $msg = 'Данные юрлица <strong>' . $row->name . '</strong> были удалены.';
            BaseModel::AddEventLog('info', $msg);
            return $this->redirect(['index']);
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    /**
     * Finds the Division model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return \app\modules\main\models\Division the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Division::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
