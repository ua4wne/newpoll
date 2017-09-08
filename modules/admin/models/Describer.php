<?php

namespace app\modules\admin\models;

use app\models\BaseModel;
use Yii;

/**
 * This is the model class for table "describer".
 *
 * @property integer $id
 * @property string $email
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Describer extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'describer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
}
