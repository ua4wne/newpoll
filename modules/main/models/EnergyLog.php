<?php

namespace app\modules\main\models;

use app\models\BaseModel;
use Yii;

/**
 * This is the model class for table "energy_log".
 *
 * @property integer $id
 * @property integer $renter_id
 * @property string $year
 * @property string $month
 * @property double $encount
 * @property double $delta
 * @property double $price
 * @property string $created_at
 * @property string $updated_at
 */
class EnergyLog extends BaseModel
{
    public $place_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'energy_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['renter_id', 'year', 'month', 'encount'], 'required'],
            [['renter_id'], 'integer'],
            [['encount', 'delta', 'price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['year'], 'string', 'max' => 4],
            [['month'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'renter_id' => 'Арендатор',
            'renter.name' => 'Арендатор',
            'year' => 'Год',
            'month' => 'Месяц',
            'encount' => 'Текущие показания, кВт.',
            'delta' => 'Потребление, кВт.',
            'price' => 'Цена, руб.',
            'place_id' => 'Территория',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenter()
    {
        return $this->hasOne(Renter::className(), ['id' => 'renter_id']);
    }

}
