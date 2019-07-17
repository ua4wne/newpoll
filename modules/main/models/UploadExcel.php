<?php
namespace app\modules\main\models;

use yii\base\Model;


class UploadExcel extends Model{

    public $fname;
    public $data;

    public function rules(){
        return[
            [['fname'], 'required', 'message' => 'Не выбран файл!'],
            [['fname'], 'file', 'extensions' => 'xls, xlsx'], //не более 512kB!!!
            [['data'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'fname' => 'Выберите Excel-файл для загрузки',
            'data' => 'Дата',
        ];
    }

    public function ReadExcelToBase(Array $sheet){
        $data = '';
        $hour = array('B'=>10,'C'=>11,'D'=>12,'E'=>13,'F'=>14,'G'=>15,'H'=>16,'I'=>17,'J'=>18,'K'=>19,'L'=>20);
        $row = 0;
        $add = -1;
        foreach ($sheet as $values){
            foreach ($values as $key=>$val){
                if($row){
                    if($key=='A') $data = $val;
                    else $ucount = $val;
                    if(!empty($data) && $ucount>0){
                        $model = new Visit();
                        $model->data = $data;
                        $model->hours = $hour[$key];
                        $model->ucount = $ucount;
                        $model->save();
                        $add++;
                    }
                }
                $row++;
            }
        }
        return $add;
    }

    public function ReadWorkToBase(Array $sheet){
        $row = 0;
        $add = -1;
        foreach ($sheet as $values){
            if($row==0) $data = $values['A'];
            if($row){
                $model = new RentLog();
                $model->renter_id = $values['A'];
                $model->data = $data;
                $model->period1 = $values['C'];
                $model->period2 = $values['D'];
                $model->period3 = $values['E'];
                $model->period4 = $values['F'];
                $model->period5 = $values['G'];
                $model->period6 = $values['H'];
                $model->period7 = $values['I'];
                $model->period8 = $values['J'];
                $model->period9 = $values['K'];
                $model->period10 = $values['L'];
                $model->period11 = $values['M'];
                $dbl = RentLog::findOne(['renter_id'=>$model->renter_id,'data'=>$model->data]);
                if(!empty($dbl)) $dbl->delete(); //удаляем дубли, если есть
                $model->save();
                $add++;
            }
            $row++;
        }
        return $add;
    }
}