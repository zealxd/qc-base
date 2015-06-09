<?php
/**
 * @link http://www.u-bo.com
 * @copyright 南京友博网络科技有限公司 
 * @license http://www.u-bo.com/license/
 */

namespace backend\assets;

/**
 * 后台主页面通用资源包
 *
 * @author legendjw <legendjw@yeah.net>
 * @since 0.1
 */
class MainAsset extends AssetBundle
{
    public $css = [
        'css/style.css',
    ];
    public $js = [
        'js/common.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'common\assets\ArtDialogAsset',
    ];
}
