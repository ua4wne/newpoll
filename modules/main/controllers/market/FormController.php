<?php

namespace app\modules\main\controllers\market;

use app\modules\main\models\CloneForm;
use app\modules\main\models\FormQty;
use app\modules\main\models\Logger;
use app\modules\main\models\Questions;
use Yii;
use app\modules\main\models\Form;
use app\modules\main\models\Answers;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\BaseModel;
use \yii\web\HttpException;

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
        if(Yii::$app->user->can('market')) {
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
        elseif(Yii::$app->user->can('poll')) {
            $this->layout = '@app/modules/user/layouts/basic.php';
            return 'Свой вывод анкет';
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    /**
     * Displays a single Form model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (Yii::$app->request->post()) {
            //$request = Yii::$app->request->post();
            $date = date('Y-m-d'); //текущая дата
            self::SavePoll($id, $date);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'content' => self::ViewForm($id),
        ]);
    }

    public function actionMedia()
    {
        if(Yii::$app->user->can('guard')) {
            $id = 7; //Опрос посетителей выставки домов Малоэтажная Страна
            $kol = 0;
            if (\Yii::$app->request->isAjax) {
                    $date = $_POST['date'];
                    $kol = $_POST['kolvo'];
                    if ($kol > 1) {
                        while ($kol) {
                            self::SavePoll($id, $date);
                            $kol--;
                        }
                    }
                    elseif ($kol==1)
                        self::SavePoll($id, $date);
                    return 'OK';

            }

            /*if(Yii::$app->request->post()){
                //$request = Yii::$app->request->post();
                $date = $_POST['date'];
                $kol = $_POST['kolvo'];
                if($kol > 0){
                    while($kol){
                        self::SavePoll($id,$date);
                        $kol--;
                    }
                }
            }*/
            return $this->render('media', [
                'model' => $this->findModel($id),
                'content' => $this->ViewMedia($id),
            ]);
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    /**
     * Creates a new Form model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->can('market')) {
            $model = new Form();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->is_work) {//если хотим установить рабочую анкеты
                    $connection = \Yii::$app->db;
                    $query = "update form set is_work=0";
                    $connection->createCommand($query)->execute();
                }
                $msg = 'Добавлена новая анкета  <strong>' . $model->name . '</strong> пользователем <strong>' . Yii::$app->user->identity->fname . ' ' . Yii::$app->user->identity->lname . '</strong>.';
                $model->save();
                BaseModel::AddEventLog('info', $msg);
                //return $this->redirect(['view', 'id' => $model->id]);
                return $this->redirect('index');
            } else {
                $statsel = array('1' => 'Активная', '0' => 'Не активная');
                $worksel = array('1' => 'Рабочая', '0' => 'Не рабочая');
                $qstcount = 0;
                return $this->render('create', [
                    'model' => $model,
                    'statsel' => $statsel,
                    'worksel' => $worksel,
                    'qstcount' => $qstcount,
                ]);
            }
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    /**
     * Clone a new Form model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionClone($id)
    {
        if(Yii::$app->user->can('market')) {
            $model = new CloneForm();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                //формируем анкету-клон
                $anket = new Form();
                $anket->name = $model->name;
                $anket->is_active = 0;
                $anket->is_work = 0;
                $anket->created_at = date('Y-m-d H:i:s');
                $anket->save();
                $questions = $model->questions;
                foreach($questions as $question){
                    $name = Questions::findOne($question)->name;
                    $new_qst = new Questions();
                    $new_qst->form_id = $anket->id;
                    $new_qst->name = $name;
                    $new_qst->save();
                    if($model->answers){
                        $answers = Answers::find()->where(['question_id' => $question])->all();
                        foreach ($answers as $answer){
                            $new_ans = new Answers();
                            $new_ans->question_id = $new_qst->id;
                            $new_ans->name = $answer->name;
                            $new_ans->htmlcode = $answer->htmlcode;
                            $new_ans->source = $answer->source;
                            $new_ans->save();
                        }
                    }
                }
                $msg = 'Добавлена новая анкета  <strong>' . $model->name . '</strong> пользователем <strong>' . Yii::$app->user->identity->fname . ' ' . Yii::$app->user->identity->lname . '</strong>.';
                $anket->save();
                BaseModel::AddEventLog('info', $msg);
                return $this->redirect('/main/market/form/index');
            } else {
                $questions = Questions::find(['form_id'=>$id])->all();
                $qst = array();
                foreach($questions as $question){
                    $qst[$question->id] = $question->name;
                    //array_push($qst,$question->name);
                }
                return $this->render('clone', [
                    'model' => $model,
                    'questions' => $qst,
                ]);
            }
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
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
        if(Yii::$app->user->can('market')) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->is_work) {//если хотим установить рабочую анкеты
                    $connection = \Yii::$app->db;
                    $query = "update form set is_work=0";
                    $connection->createCommand($query)->execute();
                }
                $msg = 'Данные анкеты  <strong>' . $model->name . '</strong> были обновлены пользователем <strong>' . Yii::$app->user->identity->fname . ' ' . Yii::$app->user->identity->lname . '</strong>.';
                $model->save();
                BaseModel::AddEventLog('info', $msg);
                //return $this->redirect(['view', 'id' => $model->id]);
                return $this->redirect('index');
            } else {
                $statsel = array('1' => 'Активная', '0' => 'Не активная');
                $worksel = array('1' => 'Рабочая', '0' => 'Не рабочая');
                $qstcount = Questions::find()->where(['=', 'form_id', $id])->count();
                return $this->render('update', [
                    'model' => $model,
                    'statsel' => $statsel,
                    'worksel' => $worksel,
                    'qstcount' => $qstcount,
                ]);
            }
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
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
        if(Yii::$app->user->can('market')) {
            //есть ли вопросы
            $qst = Questions::find()->where(['=', 'form_id', $id])->count();
            if ($qst)
                Yii::$app->session->setFlash('error', 'У анкеты есть не удаленные вопросы. Удаление не возможно!');
            else {
                $msg = 'Анкета  <strong>' . $this->findModel($id)->name . '</strong> была удалена пользователем <strong>' . Yii::$app->user->identity->fname . ' ' . Yii::$app->user->identity->lname . '</strong>.';
                $this->findModel($id)->delete();
                BaseModel::AddEventLog('info', $msg);
            }

            return $this->redirect(['index']);
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
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

    public static function ViewForm($id){
        $content='<div class="content">';
        $content.='<input type="hidden" name="form_id" id="form_id" value="'.$id.'">';
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
                if(strpos($answer->htmlcode,"select size=",0)!=false)
                {
                    $html='<option value="" selected disabled>Выберите из списка</option>';
                    $query="SELECT name FROM ".$answer->source;
                    // подключение к базе данных
                    $connection = \Yii::$app->db;
                    // Составляем SQL запрос
                    $model = $connection->createCommand($query);
                    //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
                    $rows = $model->queryAll();
                    foreach($rows as $row){
                        if($row[name]!='Другое (свой вариант)')
                            $html.='<option value="'.$row[name].'">'.$row[name].'</option>';
                    }
                    $html.='</select>';
                    $content.= '<td>'.$answer->htmlcode.$html.'</td>';
                }
                else
                    $content.= '<td>'.$answer->htmlcode.'</td>';

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
        return $content;
    }

    protected function ViewMedia($id){
        $content='<div class="content">';
        $content.='<input type="hidden" name="form_id" id="form_id" value="'.$id.'">';
        //выбираем только вопрос Откуда Вы узнали о выставке домов "Малоэтажная страна" анкеты
        $questions = Questions::find()->where(['form_id'=>$id, 'name'=>'Откуда Вы узнали о выставке домов "Малоэтажная страна"'])->all();
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
                if(strpos($answer->htmlcode,"select size=",0)!=false)
                {
                    $html='<option value="" selected disabled>Выберите из списка</option>';
                    if($answer->source =='renter'){
                        $query="SELECT title FROM ".$answer->source." WHERE status=1 AND place_id IN(1,6)";
                        // подключение к базе данных
                        $connection = \Yii::$app->db;
                        // Составляем SQL запрос
                        $model = $connection->createCommand($query);
                        //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
                        $rows = $model->queryAll();
                        foreach($rows as $row){
                            if($row[name]!='Другое (свой вариант)')
                                $html.='<option value="'.$row['title'].'">'.$row['title'].'</option>';
                        }
                        $html.='</select>';
                    }
                    else{
                        $query="SELECT name FROM ".$answer->source;
                        // подключение к базе данных
                        $connection = \Yii::$app->db;
                        // Составляем SQL запрос
                        $model = $connection->createCommand($query);
                        //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
                        $rows = $model->queryAll();
                        foreach($rows as $row){
                            if($row[name]!='Другое (свой вариант)')
                                $html.='<option value="'.$row['name'].'">'.$row['name'].'</option>';
                        }
                        $html.='</select>';
                    }

                    $content.= '<td>'.$answer->htmlcode.$html.'</td>';
                }
                else
                    $content.= '<td>'.$answer->htmlcode.'</td>';

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
            $content.='<table class="table">
                        <tr><td>
                            <label for="date">Укажите дату в формате ГГГГ-ММ-ДД: </label><input type="text" name="date" id="date" value="'.date('Y-m-d').'" placeholder="yyyy-mm-dd" required>
                        </td>
                        <td>
                            <label for="kolvo">Кол-во опрошенных </label><input type="text" name="kolvo" id="kolvo" class="digits" value="" required>
                        </td></tr>
                    </table>';
        }

        $content.='</div>';
        return $content;
    }

    public static function SavePoll($idform,$date){
        $iduser = Yii::$app->user->identity->getId(); //id пользователя
        $questions = Questions::find()->select(['id'])->where(['=','form_id',$idform])->all(); //выбрали все вопросы анкеты
        foreach ($questions as $question){
            $idx="q".$question->id; //value = id ответов
            if(isset($_POST[$idx])){
                //return '$_POST[$idx]='.$_POST[$idx];
                if(is_array($_POST[$idx]))
                {
                    foreach($_POST[$idx] as $value)
                    {
                        $answer = Answers::findOne($value);
                        if(strstr($answer['name'],'укажите')||strstr($answer['name'],'указать'))
                        {
                            $alt = "other".$answer['id'];
                            if(strlen($_POST[$alt])!=0)
                                $val = $_POST[$alt];
                            else
                                $val = 'Другое (не указано)';
                        }
                        else
                            $val = $answer['name'];
                        $model = new Logger();
                        $model->data = $date;
                        $model->form_id = $idform;
                        $model->question_id = $question->id;
                        $model->answer_id = $answer['id'];
                        $model->answer = $val;
                        $model->user_id = $iduser;
                        $model->save();
                    }
                }
                else{
                    $answer = Answers::findOne($_POST[$idx]);
                    if(strstr($answer['name'],'укажите')||strstr($answer['name'],'указать'))
                    {
                        $alt = "other".$answer['id'];
                        if(strlen($_POST[$alt])!=0)
                            $val = $_POST[$alt];
                        else
                            $val = 'Другое (не указано)';
                    }
                    else
                        $val = $answer['name']; //заполняем ассоциативный массив ответов
                    $model = new Logger();
                    $model->data = $date;
                    $model->form_id = $idform;
                    $model->question_id = $question->id;
                    $model->answer_id = $answer['id'];
                    $model->answer = $val;
                    $model->user_id = $iduser;
                    $model->save();
                }
            }
        }
        //после сохранения анкеты увеличиваем счетчик опрошенных в таблице form_qty
        $table = FormQty::findOne(['form_id'=>$idform, 'date'=>$date]);
        if(empty($table)){
            $new = new FormQty();
            $new->form_id = $idform;
            $new->date = $date;
            $new->qty = 1;
            $new->created_at = date('Y-m-d H:i:s');
            $new->updated_at = $new->created_at;
            $new->save();
        }
        else{
            $qty = $table->qty;
            $qty++;
            $table->qty = $qty;
            $table->update();
        }
    }
}
