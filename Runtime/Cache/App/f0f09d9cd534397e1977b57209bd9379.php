<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8"/>
    <title><?php echo ($town["name"]); ?></title>
    <meta name="viewport" content="width=device-width">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- head 中 -->
    <link rel="stylesheet" href="//cdn.bootcss.com/weui/1.1.1/style/weui.min.css">
    <link rel="stylesheet" href="//cdn.bootcss.com/jquery-weui/1.0.1/css/jquery-weui.min.css">
    <link rel="stylesheet" href="/Public/statics/css/mobile.css">
</head>
<body>
<header class="demos-header">
    <h1 class="demos-title"><?php echo ($town["name"]); ?></h1>
</header>
<div class="content-padded">
    <?php echo ($town["baseinfo"]); ?>
</div>
<div class="weui-grids">
    <?php if(is_array($villages)): $i = 0; $__LIST__ = $villages;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="<?php echo U('App/Index/village',array('id'=>$v['id']));?>" class="weui-grid js_grid">
        <p class="weui-grid__label">
            <?php echo ($v["name"]); ?>
        </p>
    </a><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
<div class="weui-footer">
    <p class="weui-footer__text">Copyright © 启凡科技提供技术支持</p>
</div>
</body>
<!-- body 最后 -->
<script src="//cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
<script src="//cdn.bootcss.com/jquery-weui/1.0.1/js/jquery-weui.min.js"></script>
</html>