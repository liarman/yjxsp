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
    <h1 class="demos-title">视频中心</h1>
</header>
<div class="weui-grids">
<?php if(is_array($cameras)): $i = 0; $__LIST__ = $cameras;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$camera): $mod = ($i % 2 );++$i;?><a href="<?php echo U('App/Equipment/camera',array('id'=>$camera['id']));?>" class="weui-grid js_grid weui-mygrid">
        <div class="weui-grid__icon">
            <img src="/Public/statics/images/camera.png" alt="">
        </div>
        <p class="weui-grid__label">
            <?php echo ($camera["name"]); ?>
        </p>
    </a>
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