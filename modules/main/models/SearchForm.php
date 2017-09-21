<?php

namespace app\modules\main\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class SearchForm extends Model
{
    public $start;
    public $finish;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // start, finish are required
            [['start', 'finish',], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'start' => 'Начало периода',
            'finish' => 'Конец периода',
        ];
    }

}
