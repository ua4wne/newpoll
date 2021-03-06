<?php

namespace app\modules\main\models;

use app\models\BaseModel;
use Yii;
use app\modules\admin\models\Ecounter;

/**
 * This is the model class for table "main_log".
 *
 * @property integer $id
 * @property integer $ecounter_id
 * @property string $year
 * @property string $month
 * @property double $encount
 * @property double $delta
 * @property double $price
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Ecounter $ecounter
 */
class MainLog extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'main_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ecounter_id', 'year', 'month', 'encount'], 'required'],
            [['ecounter_id'], 'integer'],
            [['encount', 'delta', 'price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['year'], 'string', 'max' => 4],
            [['month'], 'string', 'max' => 2],
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
            'ecounter_id' => 'Счетчик',
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
    public function getEcounter()
    {
        return $this->hasOne(Ecounter::className(), ['id' => 'ecounter_id']);
    }
}
