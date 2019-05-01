<?php
/**
 * Created by PhpStorm.
 * User: rogatnev
 * Date: 17.04.2019
 * Time: 13:32
 */

namespace app\modules\main\models;

use Yii;
use yii\base\Model;
use app\modules\main\models\MainLog;
use yii\db\Query;
use app\controllers\HelpController;


class ItReport extends Model
{
    public $start;
    public $finish;
    public $year;
    public $type;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // start, finish are required
            [['year', 'type'], 'required'],
            [['type','start', 'finish'], 'safe'],
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
            'type' => 'Тип отчета',
        ];
    }

    public static function GetTable($type,$year){
        $k = 1;
        switch ($type){
            case 'expense':
                $content='<br/><table class="table table-hover">
                            <tr><th>№ п\п</th><th>Статья затрат</th><th>Сумма, руб</th></tr>';
                $query=Yii::$app->db->createCommand("select c.name as cname, round(sum(price),2) as price, ex.name as expn from cost c
                                            join unit_group ug on ug.id = c.unitgroup_id
                                            join expense ex on ex.id = c.expense_id
                                            where c.created_at like '$year-%' group by c.expense_id,c.name
                                            order by c.expense_id, price DESC");
                $logs = $query->queryAll();
                $old = '';
                foreach($logs as $log){
                    if($log['expn'] != $old){
                        $content.='<tr><td colspan="3" class="info text-center">'.$log['expn'].'</td></tr>';
                        $content.='<tr><td>'.$k.'</td><td>'.$log['cname'].'</td><td>'.$log['price'].'</td></tr>';
                    }
                    else{
                        $content.='<tr><td>'.$k.'</td><td>'.$log['cname'].'</td><td>'.$log['price'].'</td></tr>';
                    }
                    $old = $log['expn'];
                    $k++;
                }
                break;
            case 'supplier':
                $content='<br/><table class="table table-hover table-striped">
                            <tr><th>№ п\п</th><th>Статья затрат</th><th>Сумма, руб</th><th>Поставщик</th></tr>';
                $query=Yii::$app->db->createCommand("select c.name as cname, round(sum(price),2) as price, s.name as sname from cost c
                                            join unit_group ug on ug.id = c.unitgroup_id
                                            join supplier s on s.id = c.supplier_id
                                            where c.created_at like '$year-%' group by c.supplier_id,c.name
                                            order by price DESC,c.supplier_id ASC");
                $logs = $query->queryAll();
                foreach($logs as $log){
                    $content.='<tr><td>'.$k.'</td><td>'.$log['cname'].'</td><td>'.$log['price'].'</td><td>'.$log['sname'].'</td></tr>';
                    $k++;
                }
                break;
        }
        $content.='</table>';
        return $content;
    }

    public static function GetGraph($type,$year){
        $data = array();
        switch ($type){
            case 'expense':
                $query=Yii::$app->db->createCommand("select round(sum(price),2) as price, ex.name as expens from cost c
                                            join unit_group ug on ug.id = c.unitgroup_id
                                            join expense ex on ex.id = c.expense_id
                                            where c.created_at like '$year-%' group by c.expense_id
                                            order by c.expense_id, price DESC");
                $logs = $query->queryAll();
                foreach($logs as $log){
                    $tmp = array();
                    $tmp['y'] = $log['expens'];
                    $tmp['a'] = $log['price'];
                    array_push($data,$tmp);
                }
                break;
            case 'supplier':
                $query=Yii::$app->db->createCommand("select round(sum(price),2) as price, s.name as sname from cost c
                                            join unit_group ug on ug.id = c.unitgroup_id
                                            join supplier s on s.id = c.supplier_id
                                            where c.created_at like '$year-%' group by c.supplier_id
                                            order by price DESC,c.supplier_id ASC");
                $logs = $query->queryAll();
                foreach($logs as $log){
                    $tmp = array();
                    $tmp['y'] = $log['sname'];
                    $tmp['a'] = $log['price'];
                    array_push($data,$tmp);
                }
                break;
        }
        return json_encode($data);
    }
}