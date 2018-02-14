<?php

namespace app\modules\admin\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "event_type".
 *
 * @property integer $id
 * @property string $text
 * @property string $code
 * @property string $flag
 * @property string $created_at
 * @property string $updated_at
 */
class EventType extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text', 'code', 'flag'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['text', 'flag'], 'string', 'max' => 70],
            [['code'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'code' => 'Code',
            'flag' => 'Flag',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
