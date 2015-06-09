<?php
/**
 * @link http://www.u-bo.com
 * @copyright 南京友博网络科技有限公司 
 * @license http://www.u-bo.com/license/
 */

namespace common\assets;

/**
 * 公共基础资源包
 *
 * 引入一个公共资源包，所有在根目录bundle文件夹下的公共资源包都继承于此，方便统一设置通用属性
 *
 * @author legendjw <legendjw@yeah.net>
 * @since 0.1
 */
class AssetBundle extends \yii\web\AssetBundle
{
    public $basePath = '@webroot/bundle';
    public $baseUrl = '@web/bundle';
}
