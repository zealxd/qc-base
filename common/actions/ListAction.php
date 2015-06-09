<?php
/**
 * @link http://www.u-bo.com
 * @copyright 南京友博网络科技有限公司 
 * @license http://www.u-bo.com/license/
 */

namespace common\actions;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * 通用的列表动作类
 * 
 * @author legendjw <legendjww@gmail.com>
 * @since 0.1
 */
class ListAction extends \yii\base\Action
{
    /**
     * @var string 当前操作模型类名
     */
    public $modelName;
    /**
     * @var string 当前视图
     */
    public $view = '/common/list';
    /**
     * @var array 关联with 
     */
    public $with;
    /**
     * @var array 排序 
     */
    public $order;
    /**
     * @var interger 分页每页记录数
     */
    public $pageSize = 15;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->modelName === null && $this->modelName = $this->controller->modelName;
        $model = new $this->modelName;

        $query = call_user_func([$this->modelName, 'find']);

        //排序，默认按照索引倒序排列
        if ($this->order !== null) {
            $query->orderBy($this->order);
        }
        elseif($pks = call_user_func([$this->modelName, 'primaryKey'])) {
            $query->orderBy([$pks[0] => SORT_DESC]);
        }

        if ($this->with !== null) {
            foreach ($this->with as $with) {
                $query->with($with);
            }
        }

        //搜索
        $request = Yii::$app->request;
        $keywords = $request->get('keywords');
        if (!empty($keywords)) {
            $field = $request->get('search_field');
            $query->where(['like', $field, $keywords]);
        }

        //列表显示
        $listAttributes = method_exists($model, 'listAttributes') ? $model->listAttributes() : [];
        $attributeLabels = method_exists($model, 'attributeLabels') ? $model->attributeLabels() : [];
        $listHandleEvents = method_exists($model, 'listHandleEvents') ? $model->listHandleEvents() : [];

        //\yii\helpers\VarDumper::dump(\yii\helpers\Url::to(), 10, true);

        //获取分页数
        $pageSize = $request->get('page_size');
        $pageSize = empty($pageSize) ? $this->pageSize : $pageSize;
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);

        return $this->controller->render($this->view, [
            'get' => $request->get(),
            'model' => new $this->modelName,
            'models' => $provider->models,
            'pages' => $provider->pagination,
            'listAttributes' => $listAttributes,
            'attributeLabels' => $attributeLabels,
            'listHandleEvents' => $listHandleEvents,
            'pageSize' => $pageSize,
        ]);
    }
		
}
