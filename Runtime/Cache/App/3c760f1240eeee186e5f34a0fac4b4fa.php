<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title></title>
    <meta name="viewport" content="width=device-width">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- head 中 -->
    <link rel="stylesheet" href="//cdn.bootcss.com/weui/1.1.1/style/weui.min.css">
    <link rel="stylesheet" href="//cdn.bootcss.com/jquery-weui/1.0.1/css/jquery-weui.min.css">
    <link rel="stylesheet" href="/Public/statics/css/mobile.css">
    <link rel="stylesheet" href="/Public/statics/bootstrap-3.3.5/css/bootstrap.css">
</head>
<body>
<div class="weui-cells_title">
    <h3 style="text-align:center"> <?php if(is_array($openinfo)): $i = 0; $__LIST__ = array_slice($openinfo,0,1,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$openinfos): $mod = ($i % 2 );++$i; echo ($openinfos["vname"]); ?>公开信息<?php endforeach; endif; else: echo "" ;endif; ?></h3>
        <table class="table table-bordered">
            <thead>
                <tr >
                    <th>名称</th>
                    <th>培养联系人</th>
                    <th>入党介绍人</th>
                    <th>当前阶段</th>
                    <th>查看</th>
                </tr>
            </thead>
            <?php if(is_array($openinfo)): $i = 0; $__LIST__ = $openinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$openinfos): $mod = ($i % 2 );++$i;?><tbody>
                    <tr>
                        <td><?php echo ($openinfos["name"]); ?></td>
                        <td><?php echo ($openinfos["peiyang"]); ?></td>
                        <td><?php echo ($openinfos["jieshao"]); ?></td>
                        <td><?php echo ($openinfos["current"]); ?></td>
                       <td>
                        <a href="<?php echo U('App/Index/lookOpeninfo',array('id'=>$openinfos['id']));?>">
                            <div>
                                <img src="/Public/statics/images/search.png" alt="">
                            </div>
                        </a>
                       </td>
                    </tr>
               </tbody><?php endforeach; endif; else: echo "" ;endif; ?>
        </table>
</div>
<div class="weui-footer">
    <p class="weui-footer__text">Copyright © 启凡科技提供技术支持</p>
</div>
</body>
<!-- body 最后 -->
<script src="//cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
<script src="//cdn.bootcss.com/jquery-weui/1.0.1/js/jquery-weui.min.js"></script>
</html>