<?php

namespace app\modules\admin\models;

use app\models\BaseModel;
use Yii;

/**
 * This is the model class for table "own_ecounter".
 *
 * @property int $id
 * @property string $name
 * @property string $text
 * @property double $koeff
 * @property double $tarif
 * @property string $created_at
 * @property string $updated_at
 */
class OwnEcounter extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'own_ecounter';
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
}
