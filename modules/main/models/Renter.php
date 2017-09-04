<?php

namespace app\modules\main\models;

//use app\modules\main\models\Division;
use app\modules\admin\models\Place;
use app\modules\main\models\Division;
use Yii;

/**
 * This is the model class for table "renter".
 *
 * @property integer $id
 * @property string $title
 * @property string $area
 * @property string $agent
 * @property string $phone1
 * @property string $phone2
 * @property string $encounter
 * @property double $koeff
 * @property integer $place_id
 * @property integer $status
 * @property integer $division_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Renter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'renter';
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
            [['title', 'area', 'agent', 'encounter', 'place_id', 'division_id'], 'required'],
            [['koeff'], 'number'],
            [['place_id', 'status', 'division_id'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['area', 'phone1', 'phone2', 'encounter'], 'string', 'max' => 20],
            [['agent'], 'string', 'max' => 50],
            [['encounter'], 'unique'],
            [['division_id'], 'exist', 'skipOnError' => true, 'targetClass' => Division::className(), 'targetAttribute' => ['division_id' => 'id']],
            [['place_id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['place_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Наименование',
            'area' => 'Участок',
            'agent' => 'Представитель',
            'phone1' => 'Телефон рабочий',
            'phone2' => 'Телефон сотовый',
            'encounter' => 'Счетчик',
            'koeff' => 'Коэффициент',
            'place_id' => 'Территория',
            'place.name' => 'Территория',
            'status' => 'Статус',
            'division_id' => 'Закреплен за',
            'division.name' => 'Закреплен за',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivision()
    {
        return $this->hasOne(Division::className(), ['id' => 'division_id']);
    }

    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id' => 'place_id']);
    }
}
