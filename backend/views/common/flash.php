<?php
use backend\assets\MainAsset;

$asset = MainAsset::register($this);
$url = ($url === null) ? 'javascript:history(-1);' : $url;
$this->title = ($type == 'success') ? '成功提示' : '失败提示' . $message;
?>

<style>
    #error_tips{
        border:1px solid #d4d4d4;
        background:#fff;
        -webkit-box-shadow: #ccc 0 1px 5px;
        -moz-box-shadow: #ccc 0 1px 5px;
        -o-box-shadow:#ccc 0 1px 5px;
        box-shadow: #ccc 0 1px 5px;
        filter: progid: DXImageTransform.Microsoft.Shadow(Strength=3, Direction=180, Color='#ccc');
        width:500px;
        margin:100px auto;
    }
    #error_tips h2{
        font:bold 14px/40px Arial;
        height:40px;
        padding:0 20px;
        margin-top:0px;
        color:#666;
        background: linear-gradient(#ffffff, #ffffff 25%, #f4f4f4) no-repeat scroll 0 0 #f9f9f9;
        border-bottom: 1px solid #dfdfdf;
    }
    .error_cont{
        padding:20px 20px 30px 80px;
        background:url(<?= $asset->baseUrl ?>/images/light.png) 20px 20px no-repeat;
        line-height:1.8;
    }
    .error_return{
        padding:10px 0 0 0;
    }
</style>

<div class="wrap">
    <div id="error_tips">
        <h2><?= ($type == 'success') ? '成功提示' : '失败提示'; ?></h2>
        <div class="error_cont">
            <ul>
                <li><?= $message ?></li>
            </ul>
            <div class="error_return "><a href="<?= $url ?>" class="btn">点击跳转</a></div>
        </div>
    </div>
</div>
<script>
    <?php if ($url !== '') : ?>
        setTimeout(function () {
            location.href = '<?= $url ?>';
        }, <?= $time ?>);
    <?php endif; ?>
</script>
