<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AccountAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'css/normalize.min.css',

        'css/site.css',
        'css/account.css',

        'https://fonts.googleapis.com/css?family=Exo+2:700,400&subset=latin,cyrillic',
        'https://fonts.googleapis.com/css?family=Noto+Sans:400,400italic&subset=latin,cyrillic',
        'https://fonts.googleapis.com/css?family=Open+Sans:700,300,400&subset=latin,cyrillic',
        'https://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700&subset=latin,cyrillic',
        'https://fonts.googleapis.com/css?family=Tinos:400,400italic&subset=latin,cyrillic',

        'https://fonts.googleapis.com/css?family=Roboto:400,300,700&subset=latin,cyrillic',
        'https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300&subset=latin,cyrillic',
    ];

    public $js = [
        '/js/jquery.arcticmodal-0.3.min.js',
        '/js/profile.js',
        '/js/jquery.maskedinput.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}