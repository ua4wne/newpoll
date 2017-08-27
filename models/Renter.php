<?php

namespace app\models;

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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'area', 'agent', 'encounter', 'place_id', 'division_id', 'created_at', 'updated_at'], 'required'],
            [['koeff'], 'number'],
            [['place_id', 'status', 'division_id', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['area', 'phone1', 'phone2', 'encounter'], 'string', 'max' => 20],
            [['agent'], 'string', 'max' => 50],
            [['encounter'], 'unique'],
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
            'phone1' => 'Телефон №1',
            'phone2' => 'Телефон №2',
            'encounter' => 'Счетчик',
            'koeff' => 'Коэффициент',
            'place_id' => 'Place ID',
            'status' => 'Статус',
            'division_id' => 'Division ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
