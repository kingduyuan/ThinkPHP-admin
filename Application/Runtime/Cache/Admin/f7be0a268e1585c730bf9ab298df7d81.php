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
    <link rel="stylesheet" href="/Public/css/style.min.css" />
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
    <script type="text/javascript" src="/Public/Admin/js/base.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/dataTable.js"></script>
</head>
<body>
<div class="row-fluid">
    <!-- start: 主要内容 -->
    <div id="content">
        <div class="row-fluid">
            <div class="box span12">
                <div class="box-header">
    <h2><i class="icon-flag"></i> 角色分配权限信息 </h2>
    <div class="box-icon">
        <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
    </div>
</div>
<div class="box-content">
    <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong> 温馨提醒 ! </strong> 管理员可以看见的导航栏目对应的权限是 (xxx 显示) 哦！
    </div>

    <form class="form-horizontal allocation" action="<?php echo U('/admin/role/create');?>" method="POST">
        <fieldset>
            <div class="control-group">
                <label class="control-label">角色名称</label>
                <div class="controls">
                    <input type="text" name="name" value="<?php echo ($role['name']); ?>" readonly="readonly" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">角色说明</label>
                <div class="controls">
                    <input type="text" name="desc" value="<?php echo ($role['desc']); ?>" required="true" rangelength="[2, 50]" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">拥有权限</label>
                <div class="controls">
                    <?php if(is_array($powers)): foreach($powers as $key=>$value): ?><label class="checkbox inline">
                            <input type="checkbox" name="powers[]" <?php if(in_array($key, $roleItems)): ?>checked="checked"<?php endif; ?> value="<?php echo ($key); ?>" /> <?php echo ($value); ?>(<?php echo ($key); ?>)
                        </label><?php endforeach; endif; ?>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"> 保存提交 </button>
                <button type="reset" class="btn"> 重置 </button>
            </div>
        </fieldset>
    </form>
</div>

<script type="text/javascript">
    $(function(){
        $('.main-menu a[href=\\/admin\\/role\\/index\\.html]').parent('li').addClass('active').parent('ul').show();
        $('.allocation').submit(function(){
            return $(this).validate(validatorError).form();
        })
    })
</script>
            </div>
        </div>
    </div>
     <!--end: 主要内容 -->
</div>
<script type="text/javascript" src="/Public/Admin/js/bootstrap.min.js"></script>
<script type="text/javascript" src='/Public/Admin/js/jquery.dataTables.min.js'></script>
<script type="text/javascript" src="/Public/Admin/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/Admin/js/validate.message.js"></script>
<script type="text/javascript" src="/Public/Admin/js/layer/layer.js"></script>
<script type="text/javascript" >
    $(function(){
        $(".btn-minimize").click(function(c) {
            c.preventDefault();
            var b = $(this).parent().parent().next(".box-content");
            if (b.is(":visible")) {
                $("i", $(this)).removeClass("icon-chevron-up").addClass("icon-chevron-down")
            } else {
                $("i", $(this)).removeClass("icon-chevron-down").addClass("icon-chevron-up")
            }
            b.slideToggle()
        });

        // 隐藏内容
        $('.btn-close:first').click(function () {
            $('.main-menu li.active a').append('<span class="label">显示</span>').addClass('isShow').bind('click', function (e) {
                e.preventDefault();
                $('.row-fluid .box:first').fadeIn();
                $('.box:gt(0)').fadeOut();
                $(this).unbind('click').find('span:last').remove();
                return false;
            });
        });
    })
</script>
</body>
</html>