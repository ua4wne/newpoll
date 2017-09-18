<?php
namespace app\modules\main\models;

use Yii;
use app\modules\main\models\Renter;
use yii\base\Model;
use yii\db\Query;

class WorkReport extends Model
{
    public $start;
    public $finish;
    public $renter_id;
    public $allrent = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['renter_id', 'start','finish'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['allrent'], 'boolean'],
            //[['renter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Renter::className(), 'targetAttribute' => ['renter_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'start' => 'Начало периода',
            'finish' => 'Конец периода',
            'renter_id' => 'Арендатор',
            'allrent' => 'Все арендаторы',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenter()
    {
        return $this->hasOne(Renter::className(), ['id' => 'renter_id']);
    }

    //выборка всех действующих арендаторов выставки
    public function GetActiveRenters(){
        $place = (new Query())->select('id')->from('place')->where(['like', 'name', 'МС']); //выбираем площадки МС
        return Renter::find()->select(['id','title','area'])->where(['place_id'=>$place,'status'=>1])->orderBy('title', SORT_ASC)->asArray()->all();
    }
}