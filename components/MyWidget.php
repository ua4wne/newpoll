<?php
/**
 * Created by PhpStorm.
 * User: rogatnev
 * Date: 31.07.2017
 * Time: 11:27
 */

namespace app\components;
use yii\base\Widget;


class MyWidget extends Widget
{
    public $name;
    public function init(){
        parent::init();
        //if($this->name === null) $this->name = 'Гость';
        ob_start(); //буферизируем весь вывод
    }

    public function run() {

        //return $this->render('my',['name'=>$this->name]);
        $content = ob_get_clean(); //помещаем в переменную ранее буферизированный вывод
        $content = mb_strtolower($content, 'utf-8');
        return $this->render('my',compact('content'));
    }
}