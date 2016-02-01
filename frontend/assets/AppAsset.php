<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;
/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'css/normalize.min.css',

        'css/site.css?v=1.0',
        'css/frontend.css?v=1.0',

        'http://fonts.googleapis.com/css?family=Exo+2:700,400&subset=latin,cyrillic',
        'http://fonts.googleapis.com/css?family=Noto+Sans:400,400italic&subset=latin,cyrillic',
        'http://fonts.googleapis.com/css?family=Open+Sans:700,300,400&subset=latin,cyrillic',
        'http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700&subset=latin,cyrillic',
        'http://fonts.googleapis.com/css?family=Tinos:400,400italic&subset=latin,cyrillic',

    ];
    public $js = [
        '/js/jquery.maskedinput.min.js'
//        'js/jquery.arcticmodal-0.3.min.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init() {
        $this->cssOptions['position'] = View::POS_HEAD;
        parent::init();
    }

}
