<?php

namespace app\modules\admin\models;

use app\modules\admin\models\Place;
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
            [['name', 'text', 'koeff'], 'required'],
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
            'name' => 'Наименование',
            'text' => 'Описание',
            'koeff' => 'Коэффициент',
            'tarif' => 'Тариф',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
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
