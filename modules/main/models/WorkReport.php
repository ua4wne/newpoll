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

    //выборка в отчет для одного арендатора
    public static function OneRenterReport($id,$s,$f)
    {
        //цикл по датам и периодам
        $logs = RentLog::find()->select(['data', 'period1', 'period2', 'period3', 'period4', 'period5', 'period6', 'period7', 'period8', 'period9', 'period10', 'period11'])
            ->where(['=', 'renter_id', $id])->andWhere(['between', 'data', $s, $f])->orderBy(['data' => SORT_ASC])->all();
        //return print_r($logs);
        $content='<table class="table table-hover table-striped">
            <tr><th>Дата\Период</th><th>10-11</th><th>11-12</th><th>12-13</th><th>13-14</th><th>14-15</th><th>15-16</th><th>16-17</th><th>17-18</th><th>18-19</th>
                <th>19-20</th><th>20-21</th>
            </tr>';
        foreach($logs as $log){
            $content .= '<tr><td>' . $log->data . '</td>';
            for ($j = 1; $j < 12; $j++) {
                $period = 'period' . $j;
                if ($log->$period == 1)
                    $content .= '<td class="success"><span class="fa fa-check"></span></td>';
                else
                    $content .= '<td class="danger"><span class="fa fa-times"></span></td>';
            }
            $content .= '</tr>';
        }
        $content.='</table>';
        return $content;
    }

    //выборка в отчет для нескольких арендаторов
    public static function RentersReport($renters,$s,$f){
        $content='<table class="table table-hover table-striped"><tbody><tr>';
        $content.='<th>№ участка</th><th>Название компании</th><th>Кол-во часов</th><th>Кол-во дней</th><th>В среднем часов в день</th></tr>';
        // подключение к базе данных
        $connection = \Yii::$app->db;
        foreach($renters as $renter){
            //группировка по датам и периодам
            $query="SELECT renter.title, renter.area, Sum(period1)+Sum(period2)+Sum(period3)+Sum(period4)+Sum(period5)+Sum(period6)+Sum(period7)+Sum(period8)+Sum(period9)+Sum(period10)+Sum(period11) AS alltime,";
            $query.="count(rent_log.data) AS alldata FROM rent_log INNER JOIN renter ON renter.id = rent_log.renter_id";
            $query.=" WHERE renter_id=". $renter ." AND rent_log.`data` BETWEEN '".$s."' AND '".$f."'";
            $query.=" GROUP BY renter.title, renter.area ORDER BY renter.area+0";
            $result = $connection->createCommand($query)->queryAll();
            //return print_r($result);
            $content.='<tr><td>'.$result[0]['area'].'</td><td>'.$result[0]['title'].'</td>';
            $content.='<td>'.$result[0]['alltime'].'</td><td>'.$result[0]['alldata'].'</td>';
            if($result[0]['alldata'] > 0)
                $avg=round($result[0]['alltime']/$result[0]['alldata'],2);
            else
                $avg = 0;
            if($avg<9)
                $content.='<td style="color:red">'.$avg.'</td></tr>';
            else
                $content.='<td>'.$avg.'</td></tr>';
        }
        $content.='</table>';
        return $content;
    }
}