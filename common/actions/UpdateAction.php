<?php
/**
 * @link http://www.u-bo.com
 * @copyright 南京友博网络科技有限公司 
 * @license http://www.u-bo.com/license/
 */

namespace common\actions;

use Yii;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;

/**
 * 通用的编辑动作类
 * 
 * @author legendjw <legendjww@gmail.com>
 * @since 0.1
 */
class UpdateAction extends \yii\base\Action
{
    /**
     * @var string 当前操作模型类名
     */
    public $modelName;
    /**
     * @var string 当前视图
     */
    public $view = '/common/create';
    /**
     * @var string 模型的场景 
     */
    public $scenario = 'default';
    /**
     * @var array 关联with 
     */
    public $with;
    /**
     * @var string 成功提示
     */
    public $successMsg = '编辑成功';
    /**
     * @var string 失败提示
     */
    public $errorMsg = '编辑失败，请稍后再试';

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

        //如果是提交数据则尝试保存
        if ($model->load($request->post())) {
            if (false !== $model->save()) {
                return $this->controller->flash(['message' => $this->successMsg, 'url' => $from]);
            } elseif ($request->isAjax) {
                //如果是ajax请求则返回错误信息而不是直接跳转到原页面
                $errors = ActiveForm::validate($model);
                return $this->controller->flash(['type' => 'error', 'message' => $this->errorMsg, 'time' => 3000, 'data' => ['errors' => $errors]]);
            }
        }

        //获取当前模型的控件属性
        $controlAttributes = method_exists($model, 'controlAttributes') ? $model->controlAttributes() : [];

        //\yii\helpers\VarDumper::dump($createAttributes, 10, true);

        return $this->controller->render($this->view, [
            'controlAttributes' => $controlAttributes,
            'get' => $request->get(),
            'model' => $model,
            'pks' => $pks,
            'from' => $from,
        ]);
    }
}
