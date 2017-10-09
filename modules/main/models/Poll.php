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
}
