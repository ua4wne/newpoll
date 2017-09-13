<?php

namespace app\modules\main\models;

use Yii;
use app\models\BaseModel;
use app\modules\main\models\Renter;
//use yii\base\Model;
use yii\db\Query;

/**
 * This is the model class for table "rent_log".
 *
 * @property integer $id
 * @property integer $renter_id
 * @property string $data
 * @property integer $period1
 * @property integer $period2
 * @property integer $period3
 * @property integer $period4
 * @property integer $period5
 * @property integer $period6
 * @property integer $period7
 * @property integer $period8
 * @property integer $period9
 * @property integer $period10
 * @property integer $period11
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Renter $renter
 */
class RentLog extends BaseModel
{
    public $period;
    public $alltime = false;
    public $allrent = false;
    public $notime = false;
    /*public $data;
    public $renter_id;
    public $period1;
    public $period2;
    public $period3;
    public $period4;
    public $period5;
    public $period6;
    public $period7;
    public $period8;
    public $period9;
    public $period10;
    public $period11;*/

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rent_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['renter_id', 'data'], 'required'],
            [['period1', 'period2', 'period3', 'period4', 'period5', 'period6', 'period7', 'period8', 'period9', 'period10', 'period11'], 'integer'],
            [['data', 'created_at', 'updated_at'], 'safe'],
            [['alltime','allrent','notime'], 'boolean'],
            //[['renter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Renter::className(), 'targetAttribute' => ['renter_id' => 'id']],
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
            'data' => 'Дата',
            'period1' => '10:00-11:00',
            'period2' => '11:00-12:00',
            'period3' => '12:00-13:00',
            'period4' => '13:00-14:00',
            'period5' => '14:00-15:00',
            'period6' => '15:00-16:00',
            'period7' => '16:00-17:00',
            'period8' => '17:00-18:00',
            'period9' => '18:00-19:00',
            'period10' => '19:00-20:00',
            'period11' => '20:00-21:00',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'period' => 'Временной период',
            'alltime' => 'Все периоды',
            'allrent' => 'Все арендаторы',
            'notime' => 'Отсутствовал весь день',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*public function getRenter()
    {
        return $this->hasOne(Renter::className(), ['id' => 'renter_id']);
    }*/

    //выборка всех действующих арендаторов выставки
    public function GetActiveRenters(){
        $place = (new Query())->select('id')->from('place')->where(['like', 'name', 'МС']); //выбираем площадки МС
        return Renter::find()->select(['id','title','area'])->where(['place_id'=>$place,'status'=>1])->orderBy('title', SORT_ASC)->asArray()->all();
    }
}
