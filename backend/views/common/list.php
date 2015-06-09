<?php
use backend\assets\MainAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\base\UnknownPropertyException;
use yii\base\UnknownMethodException;

/* @var $this \yii\web\View */

MainAsset::register($this);
//\yii\helpers\VarDumper::dump($models, 10, true);
?>

<?= $this->render('_search'); ?>

<div class="table_list">
    <table width="100%">
        
        <thead>
            <tr>
                <?php if ($listAttributes !== []): ?>
                    <?php foreach ($listAttributes as $field => $attribute): ?>
                    <td <?= isset($attribute['width']) ? 'width="' . $attribute['width'] . '"' : '' ?> width="5%"><?= isset($attribute['label']) ? $attribute['label'] : $attributeLabels[$field] ?></td>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tr>
        </thead>
            <?php if ($listAttributes !== []): ?>
                <?php if (!empty($models)): ?>
                    <?php foreach ($models as $model): ?>
                        <tr>
                        <?php foreach ($listAttributes as $field => $attribute): ?>
                            <td>
                                <?php
                                /**
                                 * 程序这里做了以下处理:
                                 *
                                 * 1. 检测是否有自定义的属性事件处理`handle`,如果有且为匿名函数则直接调用,否则调用相应的处理事件处理
                                 * 2. 检测当前模型是否含有此属性，若有则输出,否则抛出异常
                                 */
                                if (isset($attribute['handle'])) {
                                    if ($attribute['handle'] instanceof \Closure) {
                                        echo call_user_func($attribute['handle'], $field, $model);
                                    }
                                    else {
                                        $handleEvent = $attribute['handle'];
                                        if (isset($listHandleEvents[$handleEvent[0]])) {
                                            $args = [$field, $model];
                                            isset($handleEvent[1]) && $args[] = $handleEvent[1];
                                            echo call_user_func_array($listHandleEvents[$handleEvent[0]], $args);
                                        }
                                        else {
                                            throw new UnknownMethodException($handleEvent[0] . '处理事件不存在！是否调用错误？');
                                        }
                                    }
                                }
                                else if (isset($model->$field)) {
                                    echo $model->$field;
                                }
                                else {
                                    throw new UnknownPropertyException($field . '属性不存在，且属性处理事件错误！');
                                }
?>
                            </td>
                        <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <td><span style="color:red;">还没有任何数据...</span></td>
                <?php endif; ?>
            <?php endif; ?>
    </table>
</div>

<?= $this->render('_page', ['pages' => $pages, 'pageSize' => $pageSize]); ?>
