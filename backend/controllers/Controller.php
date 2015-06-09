<?php
/**
 * @link http://www.u-bo.com/
 * @copyright 南京友博网络科技有限公司 
 * @license http://www.u-bo.com/license/
 */

namespace backend\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Application;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\Response;

/**
 * 后台控制器基类
 * 
 * 所有后台控制器继承的基类，对后台的操作进行统一管理，定义公共操作
 * 
 * @author legendjw <legendjww@gmail.com>
 * @since 0.1
 */
class Controller extends \yii\web\Controller
{
    /**
     * @var string 当前操作的主模型类型
     *
     * 可以调用[[Oject::ClassName()]]获得
     */
    public $modelName;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {

            $this->requiredLogin();

            $this->modelName = $this->getModelName($action);

            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取一个通用的动作类配置，主要供[[actions]]方法使用
     *
     * @see actions()
     */
    public function getCommonActions()
    {
        return [
            'index' => [
                'class' => 'common\actions\ListAction',
            ],
            'create' => [
                'class' => 'common\actions\CreateAction',
            ],
            'view' => [
                'class' => 'common\actions\ViewAction',
            ],
            'update' => [
                'class' => 'common\actions\UpdateAction',
            ],
            'delete' => [
                'class' => 'common\actions\DeleteAction',
            ],
            'sort' => [
                'class' => 'common\actions\SortAction',
            ],
        ];
    }

    /**
     * 登录判断
     */
    protected function requiredLogin()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Yii::$app->user->loginUrl);
        }
    }

    /**
     * 获取当前正在运行的控制器的主模型的类名
     *
     * @param Action $action 当前运行的动作类
     */
    public function getModelName($action)
    {
        $modelName = '';
        $controllerId = $action->controller->id;

        //如果控制器id包含-，则模型名称为-分隔的单词的首字母大写拼接而成
        if (false !== strpos($controllerId, '-')) {
            $words = explode('-', $controllerId);
            foreach ($words as $word) {
                $modelName .= ucwords($word);
            }
        } else {
            $modelName = ucwords($action->controller->id);
        }
        $modelName = (isset($this->module->module)) ? 'backend\modules\\' . $this->module->id . '\\models\\' . $modelName : 'backend\models\\' . $modelName;

        return $modelName;
    }

    /**
     * 返回请求提示信息和数据
     *
     * 你可以使用此函数来返回请求的具体提示信息和额外的数据
     *
     * @param string|array $type 提示类型 `success`或者`error`，参数也可以是数组，方便跳跃性只设置其中的一个值
     * @param string $message 提示信息
     * @param string $url 要跳转的地址，如果参数是数组则调用 [[\yii\helpers\Url::to]] 进行处理
     * @param integer $time 跳转等待时间
     * @param string $format 返回数据的格式化类型，可参见 [[\yii\web\Response]]
     * @param array $data 额外要传递给提示页面的数据，以便扩展
     * @return html|json|jsonp|xml 格式化后的数据，默认是html页面
     * @see \yii\web\Response
     */
    public function flash($type = 'success', $message = '', $url = null, $time = 2000, $format = '', $data = [])
    {
        $request = Yii::$app->request;
        if (is_array($type)) {
            $message = (isset($type['message'])) ? $type['message'] : '';
            $url = (isset($type['url'])) ? $type['url'] : null;
            $time = (isset($type['time'])) ? $type['time'] : 2000;
            $format = (isset($type['format'])) ? $type['format'] : '';
            $data = (isset($type['data'])) ? $type['data'] : [];
            $type = (isset($type['type'])) ? $type['type'] : 'success';
        }
        
        if (empty($type)) {
            throw new InvalidParamException('backend\Controller\Controller::flash函数缺少合法的type参数！');
        }
        elseif (empty($message)) {
            $message = ($type == 'success') ? '操作成功' : '操作失败';
        }

        if (is_array($url)) {
            $url = Url::to($url);
        }

        $responseData = [
            'type' => $type,
            'message' => $message,
            'url' => $url,
            'time' => $time,
            'data' => $data,
        ];

        //如果是ajax请求且未设置数据格式化类型则采用json格式
        $format = ($request->getIsAjax() && $format === '') ? Response::FORMAT_JSON : $format;

        if ($format === '' || $format == Response::FORMAT_HTML) {
            return $this->renderPartial('/common/flash', $responseData);
        }
        else {
            Yii::$app->response->format = $format;
            return $responseData;
        }
    }
}
