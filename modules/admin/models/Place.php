<?php

namespace app\modules\admin\models;

use app\modules\admin\models\Ecounter;
use app\modules\main\models\Renter;
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
 * @property \app\models\Renter[] $renters
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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
                //$this->updated_at = date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            Yii::$app->session->setFlash('success', 'Запись добавлена!');
        } else {
            Yii::$app->session->setFlash('success', 'Запись обновлена!');
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
            'name' => 'Наименование',
            'ecounter_id' => 'Общий эл. счетчик',
            'ecounter.name' => 'Общий эл. счетчик',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
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
