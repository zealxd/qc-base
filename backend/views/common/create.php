<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $controlAttributes array 当前模型的控件属性 */
/* @var $get array 全局变量$_GET数组 */
/* @var $model yii\db\ActiveRecord 当前的AR模型 */
/* @var $pks array 当前模型所有的主键（编辑操作才有） */
/* @var $from string 上一页的地址（编辑操作才有） */

if ($model->isNewRecord) {
    $pks = [];
    $from = '';
}
$this->title = '用户管理';
?>
<?php if ($model->isNewRecord): ?>
<div class="nav">
    <div class="return"><a href="<?= Url::to(['index']) ?>">跳至列表</a></div>
</div>
<?php else: ?>
<div class="nav">
    <div class="return"><a href="<?= $from ?>">返回上一级</a></div>
</div>
<?php endif; ?>

<?= $this->render('_form', ['model' => $model, 'pks' => $pks, 'controlAttributes' => $controlAttributes, 'from' => $from]) ?>
