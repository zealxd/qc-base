<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\controls\Control;
use yii\base\InvalidConfigException;

//查看属性的基本模板
$template = "<tr><th >{label}</th><td>{value}</td></tr>";
?>
<div class="nav">
    <div class="return"><a href="<?= $from ?>">返回上一级</a></div>
</div>
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
                        $control = Control::createControl($type, $attribute, null, $model, $configs);
                        if (isset($configs['label'])) {
                            $label = $configs['label'];
                        }
                        elseif (isset($attributeLabels[$attribute])) {
                            $label = $attributeLabels[$attribute];
                        }
                        else {
                            $label = $attribute;
                        }
                        echo str_replace(['{label}', '{value}'], [$label, $control->renderValue()], $template);
                    }
                    else {
                        throw new InvalidConfigException($attribute . '属性配置里缺少控件类型参数！');
                    }
                }
            ?>

        </table>
    </div>
<?php endforeach; ?>
