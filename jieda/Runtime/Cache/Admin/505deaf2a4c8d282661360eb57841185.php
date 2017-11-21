<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>后台登录</title>
    <link rel="stylesheet" href="/Public/statics/login/css/style.css" />
    <script type="text/javascript" src="/Public/statics/js/jquery-1.10.2.min.js"></script>
    <script language="javascript">
        if (window != top)
            top.location.href = location.href;
    </script>
</head>
<body>
<section class="container">
    <div class="login">
        <h1>后台登录</h1>
        <form method="post" action="<?php echo U('Admin/Login/index');?>" id="loginForm">
            <p><input type="text" name="username" value="" placeholder="账号"></p>
            <p><input type="password" name="password" value="" placeholder="密码"></p>
            <p>
            <div id="captcha"></div>
            </p>
            <p class="submit"><input type="submit" name="commit" value="登录" id="login"></p>
        </form>
    </div>


</section>

<!-- 引入bootstrjs部分开始 -->
<script src="/Public/statics/js/jquery-1.10.2.min.js"></script>
<script src="/Public/statics/bootstrap-3.3.5/js/bootstrap.min.js"></script>
<script src="/tpl/Public/js/base.js"></script>
<script src="http://static.geetest.com/static/tools/gt.js"></script>
<script>
    $("#login").click(function () {
        if($("input[name='username']").val()==""){
            alert("账号不能为空");return false;
        }
        if($("input[name='password']").val()==""){
            alert("账号不能为空");return false;
        }
    });
    var handler = function (captchaObj) {
        // 将验证码加到id为captcha的元素里
        captchaObj.appendTo("#captcha");
    };
    // 获取验证码
    $.get("<?php echo U('Admin/Login/geetest_show_verify');?>", function(data) {
        // 使用initGeetest接口
        // 参数1：配置参数，与创建Geetest实例时接受的参数一致
        // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
        initGeetest({
            gt: data.gt,
            challenge: data.challenge,
            product: "float", // 产品形式
            offline: !data.success
        }, handler);
    },'json');
    // 检测验证码
    function check_verify(){
        // 组合验证需要用的数据
        var test=$('.geetest_challenge').val();
        var postData={
            geetest_challenge: $('.geetest_challenge').val(),
            geetest_validate: $('.geetest_validate').val(),
            geetest_seccode: $('.geetest_seccode').val()
        }
        // 验证是否通过
        $.post("<?php echo U('Admin/Login/geetest_ajax_check');?>", postData, function(data) {
            if (data==1) {
              $("#loginForm").submit();
            }else{
                alert('验证失败');
                return false;
            }
        });
    }
</script>
</body>
</html>