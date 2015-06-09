<?php
/**
 * @link http://www.u-bo.com
 * @copyright 南京友博网络科技有限公司 
 * @license http://www.u-bo.com/license/
 */

namespace backend\assets;

/**
 * 后台主框架资源包
 *
 * @author legendjw <legendjw@yeah.net>
 * @since 0.1
 */
class FrameAsset extends AssetBundle
{
    public $css = [
        'css/layout.css',
    ];
    public $js = [
        'js/common.js',
        'js/frame.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
