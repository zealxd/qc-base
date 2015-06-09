<?php
/**
 * @link http://www.u-bo.com
 * @copyright 南京友博网络科技有限公司 
 * @license http://www.u-bo.com/license/
 */

namespace common\actions;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * 通用的查看动作类
 * 
 * @author legendjw <legendjww@gmail.com>
 * @since 0.1
 */
class ViewAction extends \yii\base\Action
{
    /**
     * @var string 当前操作模型类名
     */
    public $modelName;
    /**
     * @var string 当前视图
     */
    public $view = '/common/view';
    /**
     * @var string 模型的场景 
     */
    public $scenario = 'default';
    /**
     * @var array 关联with 
     */
    public $with;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $request = Yii::$app->getRequest();
        $modelName = ($this->modelName === null) ? $this->controller->modelName : $this->modelName;
        //找到当前模型的所有主键，拼接成数组条件
        $pks = $modelName::primaryKey();
        $pkValues = [];
        $requestMethod = ($request->isGet) ? 'get' : 'post';
        foreach ($pks as $pk) {
            $pkValues[$pk] = $request->$requestMethod($pk);
        }
        $from = $request->$requestMethod('from');

        $model = $modelName::findOne($pkValues);
        if ($model === null) {
            throw new NotFoundHttpException('没有找到相应的记录!');
        }
        $model->scenario = $this->scenario;

        //获取当前模型的控件属性
        $controlAttributes = method_exists($model, 'controlAttributes') ? $model->controlAttributes() : [];

        $attributeLabels = method_exists($model, 'attributeLabels') ? $model->attributeLabels() : [];
        //\yii\helpers\VarDumper::dump($controlAttributes, 10, true);


        return $this->controller->render($this->view, [
            'controlAttributes' => $controlAttributes,
            'attributeLabels' => $attributeLabels,
            'model' => $model,
            'from' => $from,
        ]);
    }
}
