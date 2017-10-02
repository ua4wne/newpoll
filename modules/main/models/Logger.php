<?php

namespace app\modules\main\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "logger".
 *
 * @property integer $id
 * @property string $data
 * @property integer $form_id
 * @property integer $question_id
 * @property integer $answer_id
 * @property string $answer
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Answers $answer0
 * @property Form $form
 * @property Questions $question
 */
class Logger extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logger';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data', 'form_id', 'question_id', 'answer_id', 'answer'], 'required'],
            [['data', 'created_at', 'updated_at'], 'safe'],
            [['form_id', 'question_id', 'answer_id', 'user_id'], 'integer'],
            [['answer'], 'string', 'max' => 100],
            [['answer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Answers::className(), 'targetAttribute' => ['answer_id' => 'id']],
            [['form_id'], 'exist', 'skipOnError' => true, 'targetClass' => Form::className(), 'targetAttribute' => ['form_id' => 'id']],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Questions::className(), 'targetAttribute' => ['question_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data' => 'Дата',
            'form_id' => 'Анкета',
            'question_id' => 'Вопрос',
            'answer_id' => 'Ответ',
            'answer' => 'Ответ',
            'user_id' => 'Пользователь',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswer()
    {
        return $this->hasOne(Answers::className(), ['id' => 'answer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForm()
    {
        return $this->hasOne(Form::className(), ['id' => 'form_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Questions::className(), ['id' => 'question_id']);
    }
}
