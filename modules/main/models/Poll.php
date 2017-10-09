<?php

namespace app\modules\main\models;

use Yii;
use yii\base\Model;
use app\modules\main\models\Form;

/**
 * ContactForm is the model behind the contact form.
 */
class Poll extends Model
{
    public $form_id;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['form_id'], 'required'],
            [['form_id'], 'integer'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'form_id' => 'Выберите анкету',
        ];
    }

    public static function GetActiveForms(){
        return Form::find()->select(['id','name'])->where(['=','is_active',1])->all(); //выбираем активные анкеты
    }

    public function findModel($id)
    {
        if (($model = Form::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function ViewForm($id){
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
}
