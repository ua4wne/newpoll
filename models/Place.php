<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "place".
 *
 * @property integer $id
 * @property string $name
 * @property integer $ecounter_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Ecounter $ecounter
 * @property Renter[] $renters
 */
class Place extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'place';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'ecounter_id', 'created_at'], 'required'],
            [['ecounter_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['ecounter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ecounter::className(), 'targetAttribute' => ['ecounter_id' => 'id']],
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
            'ecounter_id' => 'Ecounter ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEcounter()
    {
        return $this->hasOne(Ecounter::className(), ['id' => 'ecounter_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenters()
    {
        return $this->hasMany(Renter::className(), ['place_id' => 'id']);
    }
}
