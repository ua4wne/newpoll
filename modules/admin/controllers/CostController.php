<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Expense;
use app\modules\admin\models\Supplier;
use app\modules\admin\models\UnitGroup;
use Yii;
use app\modules\admin\models\Cost;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CostController implements the CRUD actions for Cost model.
 */
class CostController extends Controller
{
    public $layout = '@app/views/layouts/main.php';
    /**
     * {@inheritdoc}
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
     * Lists all Cost models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Cost::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cost model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cost model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cost();
        $sepsel = array();
        $suppliers = Supplier::find()->select(['id','name'])->orderBy(['name'=>SORT_ASC])->asArray()->all();
        foreach($suppliers as $val) {
            $sepsel[$val['id']] = $val['name']; //массив для заполнения данных в select формы
        }
        $unitsel = array();
        $groups = UnitGroup::find()->select(['id','name'])->orderBy(['name'=>SORT_ASC])->asArray()->all();
        foreach($groups as $val) {
            $unitsel[$val['id']] = $val['name']; //массив для заполнения данных в select формы
        }
        $expensel = array();
        $expenses = Expense::find()->select(['id','name'])->orderBy(['name'=>SORT_ASC])->asArray()->all();
        foreach($expenses as $val) {
            $expensel[$val['id']] = $val['name']; //массив для заполнения данных в select формы
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'sepsel' => $sepsel,
            'unitsel' => $unitsel,
            'expensel' => $expensel,
        ]);
    }

    /**
     * Updates an existing Cost model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $sepsel = array();
        $suppliers = Supplier::find()->select(['id','name'])->asArray()->all();
        foreach($suppliers as $val) {
            $sepsel[$val['id']] = $val['name']; //массив для заполнения данных в select формы
        }
        $unitsel = array();
        $groups = UnitGroup::find()->select(['id','name'])->asArray()->all();
        foreach($groups as $val) {
            $unitsel[$val['id']] = $val['name']; //массив для заполнения данных в select формы
        }
        $expensel = array();
        $expenses = Expense::find()->select(['id','name'])->asArray()->all();
        foreach($expenses as $val) {
            $expensel[$val['id']] = $val['name']; //массив для заполнения данных в select формы
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'sepsel' => $sepsel,
            'unitsel' => $unitsel,
            'expensel' => $expensel,
        ]);
    }

    /**
     * Deletes an existing Cost model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Cost model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cost the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cost::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
