<?php

namespace app\modules\main\models;

use Yii;

/**
 * This is the model class for table "answers".
 *
 * @property integer $id
 * @property integer $question_id
 * @property string $name
 * @property string $htmlcode
 * @property string $source
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Questions $question
 */
class Answers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'answers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'name', 'htmlcode'], 'required'],
            [['question_id'], 'integer'],
            [['htmlcode'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['source'], 'string', 'max' => 20],
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
            'question_id' => 'Вопрос',
            'name' => 'Ответ',
            'htmlcode' => 'Html-разметка',
            'source' => 'Справочник',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Questions::className(), ['id' => 'question_id']);
    }
}
