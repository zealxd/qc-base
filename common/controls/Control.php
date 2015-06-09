<?php
/**
 * @link http://www.u-bo.com
 * @copyright 南京友博网络科技有限公司 
 * @license http://www.u-bo.com/license/
 */

namespace common\controls;

use Yii;
use yii\base\Component;

/**
 * html控件基类
 *
 * @author legendjw <legendjww@gmail.com>
 * @since 0.1
 */
class Control extends Component
{
    /**
     * @var array 内建的html控件列表
     */
    public static $builtInControls = [
        'text' => 'common\controls\TextControl',
        'password' => 'common\controls\PasswordControl',
        'radio' => 'common\controls\RadioControl',
        'dropDown' => 'common\controls\DropDownControl',
    ];
    /**
     * @var string 当前解析的模型的属性
     */
    public $attribute;
    /**
     * @var \yii\widgets\ActiveForm 当前模型的表单插件
     */
    public $form;
    /**
     * @var \yii\db\ActiveRecord 当前操作的模型
     */
    public $model;
    /**
     * @var string 当前控件默认的class，如果在$options参数里重新定义，则被覆盖
     */
    public $class;
    /**
     * @var string 提示信息
     */
    public $hint;
    /**
     * @var array 生成html标签的选项
     */
    public $options = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!isset($options['class']) && $this->class !== null) {
            $this->options['class'] = $this->class;
        }
    }

    /**
     * 创建控件
     */
    public static function createControl($type, $attribute, $form, $model, $params)
    {
        $params['attribute'] = $attribute;
        $params['form'] = $form;
        $params['model'] = $model;
        if ($type instanceof \Closure) {
            $params['class'] = __NAMESPACE__ . '\InlineControl';
            $params['method'] = $type;
        } else {
            if (isset(static::$builtInControls[$type])) {
                $type = static::$builtInControls[$type];
            }
            if (is_array($type)) {
                $params = array_merge($type, $params);
            } else {
                $params['class'] = $type;
            }
        }

        return Yii::createObject($params);
    }

    /**
     * 返回渲染控件的html内容
     *
     * 由继承的控件来实现各自的逻辑，所有的子类都应该重新实现此方法
     */
    public function renderHtml()
    {
        return '';
    }

    /**
     * 返回控件的值，供用户查看
     */
    public function renderValue()
    {
        return '';
    }
}
