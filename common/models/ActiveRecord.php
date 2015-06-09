<?php
/**
 * @link http://www.u-bo.com/
 * @copyright 南京友博网络科技有限公司 
 * @license http://www.u-bo.com/license/
 */

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\base\UnknownPropertyException;
use yii\base\InvalidValueException;

/**
 * 基础模型，所有AR模型都继承于此，方便统一控制
 *
 * @author legendjw <legendjww@gmail.com>
 * @since 0.1
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * 启用状态
     */
    const STATUS_ENABLE = 1;

    /**
     * 禁用状态
     */
    const STATUS_DISABLE = 0;

    /**
     * 返回状态列表
     */
    public static function getStatusItems()
    {
        return [
            self::STATUS_ENABLE => '启用',
            self::STATUS_DISABLE => '禁用',
        ];
    }

    /**
     * 列表要展示的属性字段
     */
    public function listAttributes()
    {
        return [];
    }

    /**
     * 模型的控件属性信息
     */
    public function controlAttributes()
    {
        return [];
    }

    /**
     * 通用的一些列表处理事件
     */
    public function listHandleEvents()
    {
        return [
            'empty' => function ($field, $model, array $args = []) {
                $default = ArrayHelper::getValue($args, 'default', '未知');

                return empty($model->$field) ? $default : $model->$field;
            },
            'join' => function ($field, $model, array $args = []) {
                $joinFields = ArrayHelper::getValue($args, 'joinFields', []);
                $sepa = ArrayHelper::getValue($args, 'sepa', '/');

                if (isset($args['default']) && empty($model->$field)) {
                    return $args['default'];
                }
                array_unshift($joinFields, $field);
                $joinValues = [];
                foreach ($joinFields as $field) {
                    if (isset($model->$field)) {
                        $joinValues[] = $model->$field;
                    }
                    else {
                        throw new UnknownPropertyException('访问不存在的属性' . $field . '！');
                    }
                }
                return implode($sepa, $joinValues);
            },
            'date' => function ($field, $model, array $args = []) {
                $format = ArrayHelper::getValue($args, 'format', 'Y-m-d H:i:s');

                if (isset($args['default']) && empty($model->$field)) {
                    return $args['default'];
                }
                return date($format, $model->$field);
            },
            'map' => function ($field, $model, array $args = []) {
                $mapData = ArrayHelper::getValue($args, 'mapData', []);
                if (isset($mapData[$model->$field])) {
                    return $mapData[$model->$field];
                }
                else {
                    throw new InvalidValueException('映射字段' . $model->$field . '的值没有设置！');
                }
            },
            'operation' => function ($field, $model, array $args = []) {
                $actions = ArrayHelper::getValue($args, 'actions', [
                    'view' => ['name' => '查看'],
                    'update' => ['name' => '编辑'],
                    'delete' => ['name' => '删除', 'class' => 'link-delete'],
                ]);
                $className = $model::className();
                $pks = $className::primaryKey();
                $ops = [];

                foreach ($pks as $pk) {
                    $pkValues[$pk] = $model->$pk;
                }
                foreach ($actions as $action => $data) {
                    //组装包含所有主键值的地址
                    $url = $pkValues;
                    array_unshift($url, $action);
                    $url['from'] = Url::to();

                    $ops[] = '<a href="' . Url::to($url) . '" ' . (isset($data['class']) ? 'class="' . $data['class'] .'"' : '') . ' >[' . $data['name'] . ']</a> ';
                }
                return implode('&nbsp;', $ops);
            }
        ];
    }

}
