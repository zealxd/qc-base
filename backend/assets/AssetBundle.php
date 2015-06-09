<?php
/**
 * @link http://www.u-bo.com
 * @copyright 南京友博网络科技有限公司 
 * @license http://www.u-bo.com/license/
 */

namespace backend\assets;

/**
 * 后台基础资源包
 *
 * 引入一个基础资源包，后台所有资源包都继承于此，方便统一设置通用属性
 *
 * @author legendjw <legendjw@yeah.net>
 * @since 0.1
 */
class AssetBundle extends \yii\web\AssetBundle
{
    public $sourcePath = '@backend/assets/bundle';
}
