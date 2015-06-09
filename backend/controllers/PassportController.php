<?php
/**
 * @link http://www.u-bo.com
 * @copyright 南京友博网络科技有限公司 
 * @license http://www.u-bo.com/license/
 */

namespace backend\controllers;

use Yii;
use common\models\LoginForm;
use backend\models\Admin;

/**
 * 后台登录通行控制器
 * 
 * @author legendjw <legendjww@gmail.com>
 * @since 0.1
 */
class PassportController extends \yii\web\Controller
{
    /**
     * 用户登录
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $model->userClass = Admin::className();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        } else {
            return $this->renderPartial('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 用户退出
     */
    public function actionLogout()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
        }
        return $this->goHome();
    }

}
