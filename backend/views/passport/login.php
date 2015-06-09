<?php
use backend\assets\LoginAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LoginForm */

LoginAsset::register($this);

$this->title = '登录';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <?= Html::csrfMetaTags() ?>
        <title><?= $this->title ?></title>
        <script type="text/javascript">
            if (window != top) {
                top.location.href=location.href;
            }
        </script>
        <?php $this->head() ?>
    </head>
    <body id="login">
        <?php $this->beginBody() ?>
        <div class="login">
            <div class="login_form">
                <div class="form_info">
                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form' ,
                        'fieldConfig' => ['options' => ['class' => 'field']]
                    ]); ?>

                    <?= $form->field($model, 'username')->textInput(['class' => 'text']) ?>

                    <?= $form->field($model, 'password')->passwordInput(['class' => 'text'])->label('密　码') ?>

                    <!--
                        <div class="field">
                            <label for="verify">验证码：</label>
                            <input type="text" class="text" size="13" name="verify" id="verify" placeholder="点击获取" />
                            <img src="" alt="验证码" id="verifyImg" title="点击更换验证码" style="position: absolute;cursor: pointer;display: none;" />
                        </div>
                         -->

                    <div class="field">
                        <label></label>
                        <?= Html::submitButton('', ['class' => 'button', 'name' => 'login-button' ,'style' => "margin-left:50px;_margin-left:48px"]) ?>
                    </div>
                    <div class="loading" style="display: none;text-align: center;color:#F00;" id="result"></div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
