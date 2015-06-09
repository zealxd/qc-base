
<div class="pages">
    <?= \yii\widgets\LinkPager::widget(['pagination' => $pages, 'prevPageLabel' => '上一页', 'nextPageLabel' => '下一页', 'firstPageLabel' => '首页', 'lastPageLabel' => '末页']); ?>

    <?php if($pages->totalCount != 0) : ?>
    <ul class="pagination" >
        <li class="disabled"><span>分页数：<input id="page-size" class="input" maxlength="4" style="float:right;width:30px;padding:0 0 0 2px;height:16px;line-height:15px;" type="text" data-url="<?= \yii\helpers\Url::to() ?>" data-old-page-size="<?= $pageSize ?>" value="<?= $pageSize ?>" /></span></li>
        <li class="disabled"><span>共<strong><?= $pages->totalCount ?></strong>条记录 <strong><?= $pages->page + 1 ?>/<?= $pages->pageCount ?></strong>页</span></li>
    </ul>
    <?php endif; ?>

</div>
