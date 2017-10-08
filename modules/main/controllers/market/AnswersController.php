<?php

namespace app\modules\main\controllers\market;

use app\modules\admin\models\Catalog;
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
            $dbl = Answers::find()->where(['=','name',$model->name])->andWhere(['=','question_id',$model->question_id])->count();
            if(!$dbl){
                $msg = '<strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong> добавил(а) новый ответ <strong>'.$model->name.'</strong> на вопрос  <strong>'. $qst->name .'</strong> анкеты <strong>'.$qst->form->name.'</strong>.';
                $answer = new Answers();
                $answer->question_id = $model->question_id;
                $answer->name = $model->name;
                $answer->htmlcode = $model->name;
                $answer->save();
                $last_id = $answer->id;
                switch ($model->htmlcode) {
                    case 'tradio':
                        $html='<input type="radio" name="q'.$id.'" id="a'.$last_id.'" value="'.$last_id.'" >'.$model->name;
                        break;
                    case 'tcheck':
                        $html='<input type="checkbox" name="q'.$id.'[]" id="a'.$last_id.'" value="'.$last_id.'">'.$model->name;
                        break;
                    case 'tmail':
                        $html='<label for="mail">'.$model->name.'</label><input type="email" name="mail" id="mail" value="" placeholder="login@domain" maxlength="30">';
                        break;
                    case 'tphone':
                        $html='<label for="phone">'.$model->name.'</label><input type="text" name="phone" id="phone" value="" placeholder="4951234567" maxlength="20">';
                        break;
                    case 'taddr':
                        $html='<label for="addr">'.$model->name.'</label><input type="text" name="addr" id="addr" value="" maxlength="100">';
                        break;
                    case 'tonetext':
                        $html='<input type="radio" name="q'.$id.'" id="a'.$last_id.'" value="'.$last_id.'" >'.$model->name.'<input type="text" name="other'.$last_id.'" value="">';
                        break;
                    case 'tmultext':
                        $html='<input type="checkbox" name="q'.$id.'[]" id="a'.$last_id.'" value="'.$last_id.'" >'.$model->name.'<input type="text" name="other'.$last_id.'" value="">';
                        break;
                    case 'tonesel':
                        //if($model->refbook=='city')
                        //    $html='<input type="radio" name="q'.$id.'" id="a'.$last_id.'" value="'.$last_id.'" >'.$model->name.'<input type="text" name="other'.$last_id.'" value="" placeholder="начинайте вводить текст" class="'.$model->refbook.'">';
                        //else
                            $html='<input type="radio" name="q'.$id.'" id="a'.$last_id.'" value="'.$last_id.'" >'.$model->name.'<select size="1" name="other'.$last_id.'" id="s'.$last_id.'">';
                        break;
                    case 'tmulsel':
                        //if($model->refbook=='city')
                        //    $html='<input type="checkbox" name="q'.$id.'[]" id="a'.$last_id.'" value="'.$last_id.'" >'.$model->name.'<input type="text" name="other'.$last_id.'" value="" placeholder="начинайте вводить текст" class="'.$model->refbook.'">';
                        //else
                            $html='<input type="checkbox" name="q'.$id.'[]" id="a'.$last_id.'" value="'.$last_id.'" >'.$model->name.'<select size="1" name="other'.$last_id.'" id="s'.$last_id.'">';
                        break;
                }
                if($last_id!=0) {
                    if($model->htmlcode=='tonesel'||$model->htmlcode=='tmulsel'){

                        $answer->source = $model->refbook;
                    }
                    $answer->htmlcode = $html;
                    $answer->save();
                    BaseModel::AddEventLog('info',$msg);
                }
            }
            else
                Yii::$app->session->setFlash('error', 'Такой вопрос уже есть в анкете!');
            return $this->redirect(['/main/market/answers','id'=>$model->question_id]);
        } else {
            $model->question_id = $id;
            $htmlsel = array ('tmail' => 'Контактный email','tphone' => 'Контактный телефон','taddr' => 'Контактный адрес',
                            'tradio' => 'Единичный выбор','tcheck' => 'Множественный выбор', 'tonetext' => 'Свой вариант (единичный выбор)',
                            'tmultext' => 'Свой вариант (множественный выбор)', 'tonesel' => 'Выбор из списка (единичный выбор)',
                            'tmulsel' => 'Выбор из списка (множественный выбор)');
            //выбираем доступные справочники
            $books = Catalog::find()->all();
            $refsel = array();
            foreach ($books as $book){
                $refsel[$book->nameEN] = $book->nameRU;
            }

            return $this->render('create', [
                'model' => $model,
                'htmlsel' => $htmlsel,
                'qname' => $qst->name,
                'refsel' => $refsel,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        Yii::$app->session->setFlash('error', 'Обновить вопрос нельзя! Его можно только удалить и затем создать новый.');
        return $this->render('update', [
                'model' => $model,
            ]);
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
        $question = Questions::findOne($question_id);
        $anket = $question->form->name;
        //есть ли ответы вопрос
        $ans = Logger::find()->where(['=','answer_id',$id])->count();
        if($ans)
            Yii::$app->session->setFlash('error', 'Есть статистика по ответу '.$this->findModel($id)->name.'. Удаление не возможно!');
        else {
            $msg = 'Из вопроса <strong>'.$question->name.'</strong> анкеты <strong>'.$anket.'</strong> был удален ответ  <strong>'. $this->findModel($id)->name .'</strong> пользователем <strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong>.';
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
