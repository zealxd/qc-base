<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * 首页框架
     */
    public function actionIndex()
    {
        return $this->renderPartial('index');
    }

    /**
     * 首页面板
     */
    public function actionPanel()
    {
        return $this->render('panel');
    }
}
