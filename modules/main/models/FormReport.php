<?php

namespace app\modules\main\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormReport extends Model
{
    public $start;
    public $finish;
    public $form_id;
    public $version;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, question_id and htmlcode are required
            [['start', 'form_id', 'finish', 'version'], 'required'],
            [['form_id'], 'integer'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'form_id' => 'Анкета',
            'start' => 'Начало периода',
            'finish' => 'Конец периода',
            'version' => 'Версия экспорта',
        ];
    }

}
