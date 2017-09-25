<?php

namespace app\modules\main\models;

use app\modules\admin\models\Ecounter;
use Yii;
use yii\base\Model;
use app\modules\main\models\MainLog;
use yii\db\Query;
use app\controllers\HelpController;

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

    public static function RentCountReport($year){
        $counts = (new Query())->select('id')->from('ecounter')->where(['=', 'name', 'Главный']); //выбираем счетчики арендаторов
        $data = array();
        $query=Yii::$app->db->createCommand("select month, sum(delta) as delta, sum(price) as price from main_log where ecounter_id !=1 and year='$year' group by month order by month");
        $logs = $query->queryAll();
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

    public static function OwnCountReport($year){
        $query = Yii::$app->db->createCommand("select l.month, sum(l.delta) as delta, sum(l.price) as price from main_log l
                                                join ecounter e on e.id = l.ecounter_id where e.name !='Главный' and year='$year' group by l.month order by l.month");
        $counts = $query->queryAll(); //выбираем показания общих счетчиков


        $data = array();
        $query=Yii::$app->db->createCommand("select l.month, round(sum(l.delta),2) as delta, round(sum(l.price),2) as price from energy_log l
                                                    join renter r on r.id=l.renter_id
                                                    join place p on p.id=r.place_id
                                                    where year = '$year' group by l.month order by l.month"); //выбираем показания счетчиков арендаторов
        $logs = $query->queryAll();
        //return print_r($logs);
        $tmp = array();
        for($i=0;$i<count($counts);$i++){
            $count = $counts[$i];
            $log = $logs[$i];
            if($count['id']==$log['ecounter_id'] && $count['month']==$log['month']){
                $tmp['m'] = $year.'-'.$log['month'];
                $tmp['d'] = $count['delta'] - $log['delta'];
                $tmp['p'] = $count['price'] - $log['price'];
                array_push($data,$tmp);
            }
        }
        return json_encode($data);
        //return print_r($data);
    }

    public static function DonuteGraph($year){
        $data = array();
        $main=0;
        $encount=0;
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

    public static function RentDonuteGraph($year){
        $data = array();
     //   $encount=0;
        $query=Yii::$app->db->createCommand("select p.name, round(sum(l.delta),2) as delta from energy_log l
                                                join renter r on r.id = l.renter_id
                                                join place p on p.id = r.place_id
                                                where year='$year' group by p.name order by p.name");
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
                //$encount = $encount + $log['delta'];
            }
        }
        return json_encode($data);
    }

    public static function OwnDonuteGraph($year){
        $data = array();
        $query = Yii::$app->db->createCommand("select e.name, e.id, sum(l.delta) as delta, sum(l.price) as price from main_log l
                                              join ecounter e on e.id = l.ecounter_id where e.name !='Главный' and year='$year' group by e.id order by e.id");
        $counts = $query->queryAll(); //выбираем показания общих счетчиков

        $query=Yii::$app->db->createCommand("select p.ecounter_id, round(sum(l.delta),2) as delta, round(sum(l.price),2) as price from energy_log l
                                            join renter r on r.id=l.renter_id
                                            join place p on p.id=r.place_id
                                            where year = '$year' group by p.ecounter_id order by p.ecounter_id, l.month");
        $logs = $query->queryAll();
        //return print_r($logs);
        $tmp = array();
        for($i=0;$i<count($counts);$i++){
            $count = $counts[$i];
            $log = $logs[$i];
            if($count['id']==$log['ecounter_id']){
                $tmp['label'] = $count['name'];
                $tmp['value'] = $count['delta'] - $log['delta'];
                array_push($data,$tmp);
            }
        }
        return json_encode($data);
    }

    public static function GetTable($year){
        $data = array(1=>0,0,0,0,0,0,0,0,0,0,0,0); //показания общих счетчиков, нумерация с 1
        $main = array(1=>0,0,0,0,0,0,0,0,0,0,0,0); //показания главного счетчика, нумерация с 1
        $content='<table class="table table-hover table-striped">
            <tr><th>Счетчик</th><th>Январь</th><th>Февраль</th><th>Март</th><th>Апрель</th><th>Май</th><th>Июнь</th><th>Июль</th><th>Август</th><th>Сентябрь</th>
                <th>Октябрь</th><th>Ноябрь</th><th>Декабрь</th>
            </tr>';
        $models = Ecounter::find()->all();
        foreach ($models as $model){
            $content.='<tr><td>'.$model->name.'</td>';
            $logs = MainLog::find()->select(['month','delta'])->where(['=','ecounter_id',$model->id])->andWhere(['=','year',$year])->orderBy('month', SORT_ASC)->all();
            //return print_r($logs);
            $k=1;
            foreach($logs as $log){
                if((int)$log->month == $k){
                    $content .='<td>'.$log->delta.'</td>';
                }
                else
                    $content .='<td>0</td>';
                $k++;
            }
            while($k<13){
                $content .='<td>0</td>';
                $k++;
            }
            $content .='</tr>';
            //считаем потери
            $k=1;
            if($model->name == 'Главный') {
                foreach ($logs as $log) {
                    if ((int)$log->month == $k)
                        $main[$k] = $log->delta;
                    $k++;
                }
            }
            else{
                foreach ($logs as $log) {
                    if ((int)$log->month == $k)
                        $data[$k] = $data[$k] + $log->delta;
                    else
                        $data[$k] = $data[$k] + 0;
                    $k++;
                }
            }
        }

        //выводим данные по потерям
        $content .= '<tr><td>Потери</td>';
        for($i=1; $i<13; $i++){
            $val = $main[$i] - $data[$i];
            if($val>0)
                $content .= '<td class="danger">' . $val . '</td>';
            else
                $content .= '<td class="success">' . $val . '</td>';
        }

        $content.='</tr></table>';
        return $content;
    }

    public static function GetRentTable($year){
        $content='<table class="table table-hover table-striped">
            <tr><th>Счетчик</th><th>Январь</th><th>Февраль</th><th>Март</th><th>Апрель</th><th>Май</th><th>Июнь</th><th>Июль</th><th>Август</th><th>Сентябрь</th>
                <th>Октябрь</th><th>Ноябрь</th><th>Декабрь</th>
            </tr>';
        $query=Yii::$app->db->createCommand("select p.name, l.month, round(sum(l.delta),2) as delta from energy_log l
                                                join renter r on r.id = l.renter_id
                                                join place p on p.id = r.place_id
                                                where year='$year' group by p.name, l.month order by p.name, l.month");
        $logs = $query->queryAll();
        //return print_r($logs);
        $old = 'new';
        $k=0;
        foreach($logs as $log){
            if($old != $log['name']){
                if($k>1){
                    while($k<13){
                        $content .='<td>0</td>';
                        $k++;
                    }
                    $content .='</tr>';
                }
                $k=1;
                $content .= '<tr><td>'.$log['name'].'</td>';
            }
            if((int)$log['month'] == $k){
                $content .='<td>'.$log['delta'].'</td>';
            }
            elseif((int)$log['month'] > $k){
                while($k<(int)$log['month']){
                    $content .='<td>0</td>';
                    $k++;
                }
                $content .='<td>'. $log['delta']. '</td>';
            }
            else
                $content .='<td>0</td>';
            $k++;
            $old = $log['name'];
        }
        while($k<13){
            $content .='<td>0</td>';
            $k++;
        }
        $content .='</tr>';
        $content.='</table>';
        return $content;
    }

    public static function GetOwnTable($year){
        $content='<table class="table table-hover table-striped">
            <tr><th>Счетчик</th><th>Январь</th><th>Февраль</th><th>Март</th><th>Апрель</th><th>Май</th><th>Июнь</th><th>Июль</th><th>Август</th><th>Сентябрь</th>
                <th>Октябрь</th><th>Ноябрь</th><th>Декабрь</th>
            </tr>';

        $query = Yii::$app->db->createCommand("select e.name, e.id, l.month, l.delta, l.price from main_log l
                                              join ecounter e on e.id = l.ecounter_id where e.name !='Главный' and year='$year' order by e.id, l.month");
        $counts = $query->queryAll(); //выбираем показания общих счетчиков

        $query=Yii::$app->db->createCommand("select p.ecounter_id, l.month, round(sum(l.delta),2) as delta, round(sum(l.price),2) as price from energy_log l
                                            join renter r on r.id=l.renter_id
                                            join place p on p.id=r.place_id
                                            where year = '$year' group by p.ecounter_id, l.month order by p.ecounter_id, l.month");
        $logs = $query->queryAll();
        //return print_r($logs);
        $old = 'new';
        $k=1;
        for($i=0;$i<count($counts);$i++){
            $count = $counts[$i];
            $log = $logs[$i];
            if($old != $count['name']){
                if($k > count($counts)/2){
                    while($k<13){
                        $content .='<td>0</td>';
                        $k++;
                    }
                }
                $content .= '<tr><td>'.$count['name'].'</td>';
                $k=1;
            }
            if($count['id'] == $log['ecounter_id']){
                if((int)$log['month'] == $k){
                    $delta = $count['delta'] - $log['delta'];
                    $content .='<td>'.$delta.'</td>';
                }
            }
            $old = $count['name'];
            $k++;
        }
        while($k<13){
            $content .='<td>0</td>';
            $k++;
        }
        $content .='</tr>';
        $content.='</table>';
        return $content;
    }

    public static function ViewCalculate($year,$renters){
        $content='';
        foreach($renters as $renter){
            $logs = EnergyLog::find()->select(['month','encount','delta','price'])->where(['=','renter_id',$renter])->andWhere(['=','year',$year])->orderBy('month', SORT_ASC)->all();
            $title = Renter::findOne($renter);
            $content.='<div class="agileinfo-grap">';
            $content.='<p class="text-info">Данные расчета для '.$title->title.'</p>';
            $content.='<table class="table table-hover table-striped">
            <tr><th>Месяц</th><th>Показания счетчика, кВт</th><th>Потребление, кВт</th><th>Сумма, руб</th></tr>';
            foreach ($logs as $log){
                $month = HelpController::SetMonth($log->month);
                $content.='<tr><td>'.$month.'</td><td>'.$log->encount.'</td><td>'.$log->delta.'</td><td>'.$log->price.'</td></tr>';
            }
            $content.='</table></div>';
        }
        return $content;
    }

}