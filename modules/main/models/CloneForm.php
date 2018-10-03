<?php

namespace app\modules\main\models;


use yii\base\Model;

class CloneForm extends Model {
    public $name;
    public $questions;
    public $answers = true;

    public function rules(){
        return[
            [['name','questions'], 'required', 'message' => 'Обязательное поле!'],
            [['answers'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название анкеты',
            'questions' => 'Вопросы анкеты',
            'answers' => 'Оставить ответы',
        ];
    }
}