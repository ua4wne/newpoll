<?php

namespace app\modules\main\controllers\market;

use app\modules\main\models\Answers;
use Yii;
use \yii\web\Controller;
use app\modules\main\models\Questions;
use app\modules\main\models\Form;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\BaseModel;

class QuestionsController extends Controller
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
                        'roles' => ['market']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($id)
    {
        $query = Questions::find()->where(['=','form_id',$id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
        ]);
        $anket = Form::findOne($id)->name;
        $form_id = Form::findOne($id)->id;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'anket' => $anket,
            'form_id' => $form_id,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate($id)
    {
        $model = new Questions();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //проверяем, уникальность вопроса для анкеты
            $qst = Questions::find()->where(['=','name',$model->name])->andWhere(['=','form_id',$model->form_id])->count();
            if(!$qst){
                $msg = '<strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong> добавил(а) новый вопрос <strong>'.$model->name.'</strong> в анкету  <strong>'. $model->form->name .'.';
                $model->save();
                BaseModel::AddEventLog('info',$msg);
            }
            else
                Yii::$app->session->setFlash('error', 'Такой вопрос уже есть в анкете!');
            return $this->redirect(['index','id'=>$model->form_id]);
        } else {
            $model->form_id = $id;
            $qstans = 0;
            return $this->render('create', [
                'model' => $model,
                'qstans' => $qstans,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //проверяем, уникальность вопроса для анкеты
            $qst = Questions::find()->where(['=','name',$model->name])->andWhere(['=','form_id',$model->form_id])->count();
            if(!$qst){
                $msg = 'Вопрос  <strong>'. $model->name .'</strong> анкеты <strong>'.$model->form->name.'</strong> был обновлен пользователем <strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong>.';
                $model->save();
                BaseModel::AddEventLog('info',$msg);
            }
            else
                Yii::$app->session->setFlash('error', 'Такой вопрос уже есть в анкете!');
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index','id'=>$model->form_id]);
        } else {
            $qstans = Answers::find()->where(['=','question_id',$id])->count();
            return $this->render('update', [
                'model' => $model,
                'qstans' => $qstans,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $form_id = $this->findModel($id)->form_id;
        $anket = Form::find($form_id)->all();
        //есть ли ответы вопрос
        $ans = Answers::find()->where(['=','question_id',$id])->count();
        if($ans)
            Yii::$app->session->setFlash('error', 'У вопроса есть не удаленные ответы. Удаление не возможно!');
        else {
            $msg = 'Вопрос  <strong>'. $this->findModel($id)->name .'</strong> был удален из анкеты <strong>'.$anket->name.'</strong> пользователем <strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong>.';
            $this->findModel($id)->delete();
            BaseModel::AddEventLog('info',$msg);
        }

        return $this->redirect(['index','id'=>$form_id]);
    }

    protected function findModel($id)
    {
        if (($model = Questions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
