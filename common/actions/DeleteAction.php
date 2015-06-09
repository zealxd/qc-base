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
 * 通用的删除动作类
 * 
 * @author legendjw <legendjww@gmail.com>
 * @since 0.1
 */
class DeleteAction extends \yii\base\Action
{
    /**
     * @var string 当前操作模型类名
     */
    public $modelName;
    /**
     * @var string 成功提示
     */
    public $successMsg = '删除成功';
    /**
     * @var string 错误提示
     */
    public $errorMsg = '删除失败，请稍后再试';

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

        $model = $modelName::findOne($pkValues);
        if ($model === null) {
            throw new NotFoundHttpException('没有找到相应的记录!');
        }

        if (false !== $model->delete()) {
            return $this->controller->flash(['message' => $this->successMsg]);
        }
        else {
            return $this->controller->flash(['type' => 'error', 'message' => $this->errorMsg]);
        }
    }
}
