<?php

namespace app\modules\main\models;

use Yii;

/**
 * This is the model class for table "inet".
 *
 * @property integer $id
 * @property integer $renter_id
 * @property string $connect
 * @property string $disconnect
 * @property string $ip
 * @property string $comment
 * @property string $created_at
 * @property string $updated_at
 */
class Inet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['renter_id', 'created_at'], 'required'],
            [['renter_id'], 'integer'],
            [['connect', 'disconnect', 'created_at', 'updated_at'], 'safe'],
            [['ip'], 'string', 'max' => 7],
            [['comment'], 'string', 'max' => 200],
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
            'renter_id' => 'Арендатор',
            'connect' => 'Дата подключения',
            'disconnect' => 'Дата отключения',
            'ip' => 'Тип подключения',
            'comment' => 'Комментарий',
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

    /* Геттер для названия арендатора */
    public function getRenterTitle() {
        return $this->renter['title'];
    }
}
