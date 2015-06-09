<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\controls\Control;
use yii\base\InvalidConfigException;

$form = ActiveForm::begin([
	'id' => 'main-form',
    'options' => ['class' => 'ajax-form'],
	'action' => ($model->isNewRecord) ? ['create'] : ['update'],
	'fieldConfig' => [
		'options' => ['tag' => 'tr'],
		'template' => "<th >{label}</th><td>{input}{error}</td><td>{hint}</td>",
	]
]);
?>

    <?php foreach ($controlAttributes as $block): ?>
        <div id="<?= $block['id'] ?>" class="h_a"><?= $block['name'] ?></div>
        <div class="table_full">
            <table width="100%">
                <col class="th" />
                <col width="400" />
                <col />

                <?php
                    if (!isset($block['attributes'])) {
                        throw new InvalidConfigException($block['name'] . '区间缺少控件属性！');
                    }
                    $attributes = $block['attributes'];
                    //循环所有的属性并调用指定的html控件来渲染
                    foreach ($attributes as $attribute => $configs) { 
                        if (isset($configs['type'])) {
                            $type = $configs['type'];
                            unset($configs['type']);
                            $control = Control::createControl($type, $attribute, $form, $model, $configs);
                            echo $control->renderHtml();
                        }
                        else {
                            throw new InvalidConfigException($attribute . '属性配置里缺少控件类型参数！');
                        }
                    }
                ?>

            </table>
        </div>
    <?php endforeach; ?>

    <?php
        //输入编辑记录所需的索引值
        if (!$model->isNewRecord) {
            foreach ($pks as $pk) {
                echo Html::hiddenInput($pk ,$model->$pk);
            }
        }
    ?>

	<div class="btn_wrap">
		<div class="btn_wrap_pd">
            <?php if ($model->isNewRecord): ?>
                <input type="hidden" id="jump-create" name="jump_create" value="0" />
                <?= Html::submitButton('提交', ['class' => 'btn btn_submit mr10', 'onclick' => 'javascript:document.getElementById("jump-create").value=0;', 'title' => '提交成功后跳至列表']) ?>
                <?= Html::submitButton('提交并新增', ['class' => 'btn mr10', 'onclick' => 'javascript:document.getElementById("jump-create").value=1;', 'title' => '提交成功后留在新增页面']) ?>
            <?php else: ?>
                <input type="hidden" name="from" value="<?= $from ?>" />
                <?= Html::submitButton('提交', ['class' => 'btn btn_submit mr10', 'title' => '提交成功后跳至列表']) ?>
            <?php endif; ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
