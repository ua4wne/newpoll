<?php

namespace app\modules\main\controllers\market;

use app\modules\main\models\Questions;
use Yii;
use app\modules\main\models\Form;
use app\modules\main\models\Answers;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\BaseModel;

/**
 * FormController implements the CRUD actions for Form model.
 */
class FormController extends Controller
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
        ];
    }

    /**
     * Lists all Form models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Form::find(),
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Form model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $content='<div class="content">';
        //выбираем все вопросы анкеты
        $questions = Questions::find()->where(['=','form_id',$id])->all();
        foreach($questions as $question){
            $content.='<div class="row"><div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">'.
                            $question->name . '?'.
                        '</div>
                        <div class="panel-body">';
            //выбираем все ответы на вопрос
            $answers = Answers::find()->where(['=','question_id',$question->id])->all();
            $k=0;
            $content.='<table class="table">';
            foreach ($answers as $answer){
                if($k==0)
                    $content.='<tr>';
                $content .= '<td>'.$answer->htmlcode.'</td>';
                $k++;
                if($k==2){
                    $content.='</tr>';
                    $k=0;
                }
            }
            if($k==1){
                $content.='<td></td></tr>';
            }
                            //<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>
            $content.='</table></div>
                    </div></div>
                </div>';
        }

        $content.='</div>';
        return $this->render('view', [
            'model' => $this->findModel($id),
            'content' => $content,
        ]);
    }

    /**
     * Creates a new Form model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Form();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->is_work){//если хотим установить рабочую анкеты
                $connection = \Yii::$app->db;
                $query="update form set is_work=0";
                $connection->createCommand($query)->execute();
            }
            $msg = 'Добавлена новая анкета  <strong>'. $model->name .'</strong> пользователем <strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong>.';
            $model->save();
            BaseModel::AddEventLog('info',$msg);
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect('index');
        } else {
            $statsel = array ('1' => 'Активная','0' => 'Не активная');
            $worksel = array ('1' => 'Рабочая','0' => 'Не рабочая');
            $qstcount = 0;
            return $this->render('create', [
                'model' => $model,
                'statsel' => $statsel,
                'worksel' => $worksel,
                'qstcount' => $qstcount,
            ]);
        }
    }

    /**
     * Updates an existing Form model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->is_work){//если хотим установить рабочую анкеты
                $connection = \Yii::$app->db;
                $query="update form set is_work=0";
                $connection->createCommand($query)->execute();
            }
            $msg = 'Данные анкеты  <strong>'. $model->name .'</strong> были обновлены пользователем <strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong>.';
            $model->save();
            BaseModel::AddEventLog('info',$msg);
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect('index');
        } else {
            $statsel = array ('1' => 'Активная','0' => 'Не активная');
            $worksel = array ('1' => 'Рабочая','0' => 'Не рабочая');
            $qstcount = Questions::find()->where(['=','form_id',$id])->count();
            return $this->render('update', [
                'model' => $model,
                'statsel' => $statsel,
                'worksel' => $worksel,
                'qstcount' => $qstcount,
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
        //есть ли вопросы
        $qst = Questions::find()->where(['=','form_id',$id])->count();
        if($qst)
            Yii::$app->session->setFlash('error', 'У анкеты есть не удаленные вопросы. Удаление не возможно!');
        else {
            $msg = 'Анкета  <strong>'. $this->findModel($id)->name .'</strong> была удалена пользователем <strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong>.';
            $this->findModel($id)->delete();
            BaseModel::AddEventLog('info',$msg);
        }

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
        if (($model = Form::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
