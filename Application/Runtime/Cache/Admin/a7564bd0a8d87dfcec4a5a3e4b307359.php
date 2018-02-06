<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>我的个人微博后台管理</title>
    <meta name="description"    content="我的个人微博后台管理" />
    <meta name="author"         content="liujx" />
    <meta name="keyword"        content="我的个人微博后台管理" />
    <meta name="viewport"       content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- 加载CSS -->
    <link rel="stylesheet" href="/Public/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/Public/css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="/Public/css/style.min.css" />
    <link rel="stylesheet" href="/Public/css/style-responsive.min.css" />
    <link rel="stylesheet" href="/Public/css/retina.css" />
    <link rel="stylesheet" href="/Public/css/jquery.datetimepicker.css" />
    <link rel="stylesheet" href="/Public/js/jcrop/jquery.Jcrop.min.css" />

    <!-- 判断IE的CSS -->
    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <link id="ie-style" href="css/ie.css" rel="stylesheet">
    <![endif]-->
    <!--[if IE 9]>
    <link id="ie9style" href="/Public/css/ie9.css" rel="stylesheet">
    <![endif]-->
    <script type="text/javascript" src="/Public/Admin/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/layer/layer.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/base.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/dataTable.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/validate.message.js"></script>
</head>
<body>

<div class="container-fluid-full">
	<div class="row-fluid">
		<div class="login-box">
			<h2>用户登录:</h2>
			<form class="form-horizontal login" name="login" action="#" method="post">
				<fieldset>
					<input class="input-large span12" name="username" id="username" type="text" placeholder="用户名" required="true" rangelength="[2,20]" />
					<input class="input-large span12" name="password" id="password" type="password" placeholder="密码" required="true" rangelength="[6,15]" />
					<div class="clearfix"></div>
					<button type="submit" class="btn btn-primary span12">登录</button>
				</fieldset>	
			</form>	
		</div>
	</div>	
</div>
<script type="text/javascript">
	$(function(){
		// 登录验证
		$('.login').submit(function(){
			// 验证通过
			if ($(this).validate().form())
			{
				var intLoad = layer.load();
				// ajax请求登录
				$.ajax({
					"url": "<?php echo U('Index/login');?>",
					"type": "POST",
					"data": $(this).serialize(),
					"dataType": "json"
				}).always(function(){
					layer.close(intLoad);
				}).done(function(json) {
					layer.tips(json.msg, ".btn-primary", {tips: [3, (json.status == 1 ? "#78BA32" : "")], time:1000});
					if (json.status == 1) window.location.href = "<?php echo U('index/index');?>";
				}).fail(function(){
					layer.msg("服务器繁忙，请稍候再试...", {time:800})
				});
			}

			return false;
		});
	});
</script>
</body>
</html>