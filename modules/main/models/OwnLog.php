<?php

namespace app\modules\main\models;

use app\models\BaseModel;
use app\modules\admin\models\OwnEcounter;
use Yii;

/**
 * This is the model class for table "own_log".
 *
 * @property int $id
 * @property int $own_ecounter_id
 * @property string $year
 * @property string $month
 * @property double $encount
 * @property double $delta
 * @property double $price
 * @property string $created_at
 * @property string $updated_at
 *
 * @property OwnEcounter $ownEcounter
 */
class OwnLog extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'own_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['own_ecounter_id', 'year', 'month', 'encount'], 'required'],
            [['own_ecounter_id'], 'integer'],
            [['encount', 'delta', 'price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['year'], 'string', 'max' => 4],
            [['month'], 'string', 'max' => 2],
            [['own_ecounter_id'], 'exist', 'skipOnError' => true, 'targetClass' => OwnEcounter::className(), 'targetAttribute' => ['own_ecounter_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'own_ecounter_id' => 'Счетчик',
            'year' => 'Год',
            'month' => 'Месяц',
            'encount' => 'Текущие показания, кВт',
            'delta' => 'Потребление',
            'price' => 'Цена',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwnEcounter()
    {
        return $this->hasOne(OwnEcounter::className(), ['id' => 'own_ecounter_id']);
    }
}
