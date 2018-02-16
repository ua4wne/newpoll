<?php

namespace app\modules\main\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "visitor".
 *
 * @property integer $id
 * @property string $fname
 * @property string $mname
 * @property string $lname
 * @property string $image
 * @property integer $renter_id
 * @property integer $status
 * @property string $car_type
 * @property string $car_num
 * @property string $doc_type
 * @property string $doc_series
 * @property string $doc_num
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Card[] $cards
 * @property Renter $renter
 */
class Visitor extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'visitor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fname', 'lname', 'renter_id'], 'required'],
            [['renter_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['fname', 'mname', 'lname'], 'string', 'max' => 50],
            [['image', 'car_type', 'doc_type'], 'string', 'max' => 30],
            [['car_num', 'doc_num'], 'string', 'max' => 10],
            [['doc_series'], 'string', 'max' => 7],
            [['renter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Renter::className(), 'targetAttribute' => ['renter_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fname' => 'Имя',
            'mname' => 'Отчество',
            'lname' => 'Фамилия',
            'image' => 'Фото',
            'renter_id' => 'Арендатор',
            'status' => 'Статус',
            'car_type' => 'Автомобиль',
            'car_num' => 'Номер ТС',
            'doc_type' => 'Документ',
            'doc_series' => 'Серия',
            'doc_num' => 'Номер',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCards()
    {
        return $this->hasMany(Card::className(), ['visitor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenter()
    {
        return $this->hasOne(Renter::className(), ['id' => 'renter_id']);
    }
}
