<?php

namespace app\modules\main\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "form_qty".
 *
 * @property integer $id
 * @property string $year
 * @property string $month
 * @property integer $qty
 * @property string $created_at
 * @property string $updated_at
 */
class FormQty extends ActiveRecord
{
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
                $this->updated_at = date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'form_qty';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'form_id', 'qty'], 'required'],
            [['form_id', 'qty'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['date'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'form_id' => 'Анкета',
            'date' => 'Дата',
            'qty' => 'Кол-во опрошенных',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
}
