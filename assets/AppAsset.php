<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    //    'css/bootstrap.min.css',
        'css/style.css',
        'css/morris.css',
        'css/font-awesome.css',
        'css/metisMenu.min.css',
        'css/select2.min.css',
        //'//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400',
        //'//fonts.googleapis.com/css?family=Montserrat:400,700',
        //'css/icon-font.min.css',
    ];
    public $js = [
    //    'js/jquery-2.1.4.min.js',
        'js/jquery.nicescroll.js',
        'js/scripts.js',
    //    'js/bootstrap.min.js',
        'js/metisMenu.min.js',
        'js/raphael-min.js',
        'js/morris.js',
        'js/control.js',
        'js/sb-admin-2.js',
        'js/select2.min.js',
    ];
    public $jsOptions = [
        //'position' => \yii\web\View::POS_HEAD, //влияет на работу фильтрации в таблицах представлений - виджет GridView!!!
    ];
    public $depends = [
        'app\assets\IESupportAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
