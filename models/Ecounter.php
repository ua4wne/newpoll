<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ecounter".
 *
 * @property integer $id
 * @property string $name
 * @property string $text
 * @property double $koeff
 * @property double $tarif
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Place[] $places
 */
class Ecounter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ecounter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'text', 'koeff', 'created_at'], 'required'],
            [['koeff', 'tarif'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['text'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'text' => 'Text',
            'koeff' => 'Koeff',
            'tarif' => 'Tarif',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces()
    {
        return $this->hasMany(Place::className(), ['ecounter_id' => 'id']);
    }
}
