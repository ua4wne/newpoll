<?php

namespace app\modules\main\models;

use app\models\BaseModel;
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
class Renter extends BaseModel
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
            [['title', 'name', 'area', 'agent', 'encounter', 'place_id', 'division_id'], 'required'],
            [['koeff'], 'number'],
            [['place_id', 'status', 'division_id'], 'integer'],
            [['title','name'], 'string', 'max' => 100],
            [['area', 'phone1', 'phone2', 'encounter'], 'string', 'max' => 20],
            [['agent'], 'string', 'max' => 50],
         //   [['encounter'], 'unique'],
        //    [['division_id'], 'exist', 'skipOnError' => true, 'targetClass' => Division::className(), 'targetAttribute' => ['division_id' => 'id']],
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
            'title' => 'Юрлицо',
            'name' => 'Наименование',
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

    /* Геттер для названия подразделения */
    public function getDivisionName() {
        return $this->division_id['name'];
    }

    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id' => 'place_id']);
    }

    /* Геттер для названия территории */
    public function getPlaceName() {
        return $this->place['name'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnergyLog()
    {
        return $this->hasMany(EnergyLog::className(), ['renter_id' => 'id']);
    }

    public function getRentLog()
    {
        return $this->hasMany(RentLog::className(), ['renter_id' => 'id']);
    }

    public static function getRenters($place_id){
        $rows = Renter::find()->where(['place_id'=>$place_id])->select(['id','title','area'])->orderBy(['title'=>SORT_ASC])->all();
        $content = '';
        foreach ($rows as $row){
            $content .= '<option value="'. $row->id .'">'. $row->title . ' ('. $row->area .')</option>';
        }
        return $content;
    }
}
