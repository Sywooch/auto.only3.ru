<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

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
        'css/site.css',

        "https://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700&subset=latin,cyrillic",
        "https://fonts.googleapis.com/css?family=Noto+Sans:400,400italic&subset=latin,cyrillic",
        "https://fonts.googleapis.com/css?family=Roboto:400,300,700&subset=latin,cyrillic"
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}