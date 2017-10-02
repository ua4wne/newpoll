<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "catalog".
 *
 * @property integer $id
 * @property string $nameEN
 * @property string $nameRU
 * @property string $created_at
 * @property string $updated_at
 */
class Catalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nameEN', 'nameRU'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['nameEN', 'nameRU'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nameEN' => 'Название En',
            'nameRU' => 'Название Ru',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
}
