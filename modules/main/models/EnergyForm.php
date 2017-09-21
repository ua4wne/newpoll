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
            $tmp['m'] = $year.'-'.$log['month'];
            $tmp['d'] = $log['delta'];
            $tmp['p'] = $log['price'];
            array_push($data,$tmp);
        }
        return json_encode($data);
        //return print_r($data);
    }

    public static function DonuteGraph($year){
        $data = array();
        $main=0;
        $encount=0;
        $err=0;
        $query=Yii::$app->db->createCommand("select e.name,sum(l.delta) as delta from main_log l
                                            join ecounter e on e.id = l.ecounter_id
                                            where year='$year' group by ecounter_id");
        $logs = $query->queryAll();
        //return print_r($logs);
        foreach($logs as $log){
            $tmp = array();
            if($log['name']=='Главный'){
                $main = $log['delta'];
            }
            else{
                $tmp['label'] = $log['name'];
                $tmp['value'] = $log['delta'];
                array_push($data,$tmp);
                $encount = $encount + $log['delta'];
            }
        }
        $err = $main - $encount;
        $tmp['label'] = 'Потери';
        $tmp['value'] = $err;
        array_push($data,$tmp);
        return json_encode($data);
    }

    public static function GetTable($year){
        $data = array();
        $content='<table class="table table-hover table-striped">
            <tr><th>Счетчик</th><th>Январь</th><th>Февраль</th><th>Март</th><th>Апрель</th><th>Май</th><th>Июнь</th><th>Июль</th><th>Август</th><th>Сентябрь</th>
                <th>Октябрь</th><th>Ноябрь</th><th>Декабрь</th>
            </tr>';
        $query=Yii::$app->db->createCommand("select e.name,sum(l.delta) as delta from main_log l
                                            join ecounter e on e.id = l.ecounter_id
                                            where year='$year' group by ecounter_id");
        $logs = $query->queryAll();
        //return print_r($logs);
        foreach($logs as $log){
            $tmp = array();
            $tmp['label'] = $log['name'];
            $tmp['value'] = $log['delta'];
            array_push($data,$tmp);
        }

        $content.='</table>';
        return $content;
    }

}
