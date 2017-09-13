<?php

namespace app\modules\main\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "visit".
 *
 * @property integer $id
 * @property string $data
 * @property string $hours
 * @property integer $ucount
 * @property string $created_at
 * @property string $updated_at
 */
class Visit extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'visit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data', 'hours', 'ucount'], 'required'],
            [['data', 'created_at', 'updated_at'], 'safe'],
            [['ucount'], 'integer'],
            //[['hours'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data' => 'Дата',
            'hours' => 'Период времени',
            'ucount' => 'Кол-во посетителей',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
}
