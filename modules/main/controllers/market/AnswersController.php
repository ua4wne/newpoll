<?php

namespace app\modules\main\controllers\market;

use app\modules\main\models\AnswerForm;
use app\modules\main\models\Answers;
use app\modules\main\models\Logger;
use Yii;
use \yii\web\Controller;
use app\modules\main\models\Questions;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\BaseModel;

class AnswersController extends Controller
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

    public function actionIndex($id)
    {
        $query = Answers::find()->where(['=','question_id',$id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
        ]);
        $qname = Questions::findOne($id)->name;
        $qid = Questions::findOne($id)->id;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'qname' => $qname,
            'qid' => $qid,
        ]);
    }

    public function actionCreate($id)
    {
        $model = new AnswerForm();
        $model->question_id = $id;
        $qst = Questions::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //проверяем, уникальность ответа для вопроса
            $qst = Answers::find()->where(['=','name',$model->name])->andWhere(['=','question_id',$model->question_id])->count();
            if(!$qst){
                /*$msg = '<strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong> добавил(а) новый ответ <strong>'.$model->name.'</strong> в анкету  <strong>'. $qst->form->name .'.';
                $answer = new Answers();
                $answer->save();
                BaseModel::AddEventLog('info',$msg);*/
            }
            else
                Yii::$app->session->setFlash('error', 'Такой вопрос уже есть в анкете!');
            return $this->redirect(['index','id'=>$model->question_id]);
        } else {
            $model->question_id = $id;
            $htmlsel = array ('tmail' => 'Контактный email','tphone' => 'Контактный телефон','taddr' => 'Контактный адрес',
                            'tradio' => 'Единичный выбор','tcheck' => 'Множественный выбор', 'tonetext' => 'Свой вариант (единичный выбор)',
                            'tmultext' => 'Свой вариант (множественный выбор)', 'tonesel' => 'Выбор из списка (единичный выбор)',
                            'tmulsel' => 'Выбор из списка (множественный выбор)');

            return $this->render('create', [
                'model' => $model,
                'htmlsel' => $htmlsel,
                'qname' => $qst->name,
            ]);
        }
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionDelete($id)
    {
        $question_id = $this->findModel($id)->question_id;
        //есть ли ответы вопрос
        $ans = Logger::find()->where(['=','answer_id',$id])->count();
        if($ans)
            Yii::$app->session->setFlash('error', 'Есть статистика по ответу '.$this->findModel($id)->name.'. Удаление не возможно!');
        else {
            $msg = 'Ответ  <strong>'. $this->findModel($id)->name .'</strong> был удален пользователем <strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong>.';
            $this->findModel($id)->delete();
            BaseModel::AddEventLog('info',$msg);
        }

        return $this->redirect(['index','id'=>$question_id]);
    }

    protected function findModel($id)
    {
        if (($model = Answers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
