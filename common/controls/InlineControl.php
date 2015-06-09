<?php
/**
 * @link http://www.u-bo.com
 * @copyright 南京友博网络科技有限公司 
 * @license http://www.u-bo.com/license/
 */

namespace common\controls;

use Yii;

/**
 * 行内控件
 *
 * @author legendjw <legendjww@gmail.com>
 * @since 0.1
 */
class InlineControl extends Control
{
    /**
     * @var Closure 匿名函数
     */
    public $method;

    /**
     * @inheritdoc
     */
    public function renderHtml()
    {
        return call_user_func($this->method, $this->attribute, $this->form, $this->model);
    }
}
