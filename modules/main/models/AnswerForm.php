<?php

namespace app\modules\main\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class AnswerForm extends Model
{
    public $name;
    public $question_id;
    public $htmlcode;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, question_id and htmlcode are required
            [['name', 'question_id', 'htmlcode'], 'required'],
            [['question_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'question_id' => 'Вопрос',
            'name' => 'Ответ на вопрос',
            'htmlcode' => 'Тип ответа',
        ];
    }

}
