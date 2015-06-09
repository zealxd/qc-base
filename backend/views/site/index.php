<?php
use backend\assets\FrameAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

FrameAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <script>
        if (window.top !== window.self) {
            document.write = '';
            window.top.location.href = window.self.location.href;
            setTimeout(function () {
                document.body.innerHTML = '';
            }, 0);
        }
var SUBMENU = {
"custom":{"id":"custom","name":"系统","icon":"","tip":"","parent":"root","top":"","items":{"custom_set":{"id":"custom_set","name":"用户管理","icon":"","tip":"","parent":"custom","top":"","url":"","items":{"custom_set":{"id":"custom_set","name":"用户列表","icon":"","tip":"","parent":"custom","top":"","url":"admin.php?r=admin/index"},"custom_set1":{"id":"custom_set","name":"新增用户","icon":"","tip":"","parent":"custom","top":"","url":"admin.php?r=admin/create"}  }}}}
};
    </script>

    <?php $this->head() ?>

    <style>
        .fullScreen .content th{display:none;width:0;}
        .fullScreen .head,.fullScreen .tab{height:0;display:none;}
        .fullScreen #default{*left:0;*top:-90px;}
        .fullScreen div.options{top:0;}
    </style>
</head>
<body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <noscript><h1 class="noscript">您已禁用脚本，这样会导致页面不可用，请启用脚本后刷新页面</h1></noscript>
        <table width="100%" height="100%" style="table-layout:fixed;">
            <tr class="head">
                <th><a href="" class="logo">管理中心</a></th>
                <td>
                    <div class="nav">
                        <!-- 菜单异步获取，采用json格式，由js处理菜单展示结构 -->
                        <ul id="J_B_main_block">

                        </ul>
                    </div>
                    <div class="login_info">
                    <a href="" class="home" target="_blank">前台首页</a><span class="mr10">管理员： admin</span><a href="<?= Url::to(['passport/logout']) ?>" class="mr10">[注销]</a>
                </div></td>
            </tr>
            <tr class="tab">
                <th>
                    <div class="search">
                        <input size="15" placeholder="" id="J_search_keyword" type="text">
                        <button type="button" name="keyword" id="J_search" value="" data-url="">搜索</button>
                </div></th>
                <td>
                    <div id="B_tabA" class="tabA">
                        <a href="" tabindex="-1" class="tabA_pre" id="J_prev" title="上一页">上一页</a>
                        <a href="" tabindex="-1" class="tabA_next" id="J_next" title="下一页">下一页</a>
                        <div style="margin:0 25px;min-height:1px;">
                            <div style="position:relative;height:30px;width:100%;overflow:hidden;">
                                <ul id="B_history" style="white-space:nowrap;position:absolute;left:0;top:0;">
                                    <li class="current" data-id="default" tabindex="0"><span><a>后台首页</a></span></li>
                                </ul>
                            </div>
                        </div>
                </div></td>
            </tr>
            <tr class="content">
                <th  style="overflow:hidden;">
                    <div id="B_menunav">
                        <div class="menubar">
                            <dl id="B_menubar">
                                <dt class="disabled"></dt>
                            </dl>
                        </div>
                        <div id="menu_next" class="menuNext" style="display:none;">
                            <a href="" class="pre" title="顶部超出，点击向下滚动">向下滚动</a>
                            <a href="" class="next" title="高度超出，点击向上滚动">向上滚动</a>
                        </div>
                    </div>
                </th>
                <td id="B_frame">
                    <div id="breadCrumb" style="display:none;">
                        首页<em>&gt;</em>功能<em>&gt;</em>功能
                    </div>
                    <div class="options">
                        <a href="" class="refresh" id="J_refresh" title="刷新">刷新</a>
                        <a href="" id="J_fullScreen" class="full_screen" title="全屏">全屏</a>
                    </div>
                    <div class="loading" id="loading">加载中...</div>
                    <iframe id="iframe_default" src="<?= Url::to(['panel']) ?>" style="height: 100%; width: 100%;display:none;" data-id="default" frameborder="0" scrolling="auto"></iframe>
                </td>
            </tr>
        </table>
    </div>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
