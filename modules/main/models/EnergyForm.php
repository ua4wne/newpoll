<?php

namespace app\modules\main\models;

use Yii;
use yii\base\Model;
use app\modules\main\models\MainLog;

/**
 * ContactForm is the model behind the contact form.
 */
class EnergyForm extends Model
{
    public $start;
    public $finish;
    public $year;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // start, finish are required
            [['start', 'finish','year'], 'required'],
            [['year'], 'string', 'max' => 4, 'min' => 4],

        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'start' => 'Начало периода',
            'finish' => 'Конец периода',
            'year' => 'Укажите год',
        ];
    }

    public static function CountReport($id,$year){
        $logs = MainLog::find()->select(['month','delta','price'])->where(['ecounter_id'=>$id,'year'=>$year])->orderBy('month', SORT_ASC)->asArray()->all();

        $data = array();
        //$query=Yii::$app->db->createCommand("select `data`, sum(ucount) as ucount from visit where `data` between '$model->start' and '$model->finish' group by `data`");
        //$logs = $query->queryAll();
        //return print_r($logs);
        foreach($logs as $log){
            $tmp = array();
            $tmp['m'] = $log['month'];
            $tmp['d'] = $log['delta'];
            $tmp['p'] = $log['price'];
            array_push($data,$tmp);
        }
        return json_encode($data);
    }

}
