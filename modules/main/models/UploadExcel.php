<?php
namespace app\modules\main\models;

use yii\base\Model;


class UploadExcel extends Model{

    public $fname;

    public function rules(){
        return[
            [['fname'], 'required', 'message' => 'Не выбран файл!'],
            [['fname'], 'file', 'extensions' => 'xls, xlsx'], //не более 512kB!!!
        ];
    }

    public function attributeLabels()
    {
        return [
            'fname' => 'Выберите Excel-файл для загрузки',
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
}