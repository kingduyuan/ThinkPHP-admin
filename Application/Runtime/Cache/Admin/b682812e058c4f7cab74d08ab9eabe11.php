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
                <style type="text/css">.mt-text span {padding-left: 10px;}</style>
<link rel="stylesheet" type="text/css" href="/Public/Admin/js/jcrop/jquery.Jcrop.min.css" />
<script type="text/javascript" src="/Public/Admin/js/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/Public/Admin/js/plupload/zh_CN.js"></script>
<script type="text/javascript" src="/Public/Admin/js/jcrop/jquery.Jcrop.min.js"></script>
<div class="box-header">
    <h2><i class="icon-list"></i>登录信息</h2>
</div>
<div class="box-content">
    <ul class="nav tab-menu nav-tabs" id="myTab">
        <li><a href="#system" id="system-a">系统信息</a></li>
        <li><a href="#info"   id="password-a">修改密码</a></li>
        <li><a href="#messages" id="message-a">我的信息</a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane" id="system">
            <ul class="dashboard-list">
                <li>
                    <div class="row-fluid">
                        <div class="span2 mt-text">
                            <i class="icon-desktop blue"></i>
                            <span class="blue">系统信息</span>
                        </div>
                        <div class="span10">
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row-fluid">
                        <div class="span2 mt-text">
                            <span>操作系统 & 内核版本</span>
                        </div>
                        <div class="span10"><?php echo ($data["system"]); ?></div>
                    </div>
                </li>
                <li>
                    <div class="row-fluid">
                        <div class="span2 mt-text">
                            <span>服务器软件</span>
                        </div>
                        <div class="span10"><?php echo ($data["server"]); ?></div>
                    </div>
                </li>
                <li>
                    <div class="row-fluid">
                        <div class="span2 mt-text">
                            <span>MySQL版本</span>
                        </div>
                        <div class="span10"><?php echo ($data["mysql"]); ?></div>
                    </div>
                </li>
                <li>
                    <div class="row-fluid">
                        <div class="span2 mt-text">
                            <span>PHP 版本</span>
                        </div>
                        <div class="span10"><?php echo ($data["php"]); ?></div>
                    </div>
                </li>
                <li>
                    <div class="row-fluid">
                        <div class="span2 mt-text">
                            <span>ThinkPHP版本</span>
                        </div>
                        <div class="span10"><?php echo ($data["tp"]); ?></div>
                    </div>
                </li>
                <li>
                    <div class="row-fluid">
                        <div class="span2 mt-text">
                            <span>上传文件</span>
                        </div>
                        <div class="span10"><?php echo ($data["upload"]); ?></div>
                    </div>
                </li>
                <li>
                    <div class="row-fluid">
                        <div class="span2 mt-text">
                            <i class=" icon-warning-sign red"></i>
                            <span class="red">安全警告</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row-fluid">
                        <div class="span10 mt-text">
                            <span class="text-danger red"> 禁止把源码给予（或出售）第三方，会失去售后和永久更新;  如果您的网站出现问题，请记住您的操作，方便技术处理; 有任何技术或运营问题，请及时联系在线客服或是售后服务 </span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="tab-pane active" id="messages">
            <ul class="dashboard-list">
                <li>
                    <div class="row-fluid">
                        <div class="span2 mt-text">
                            <i class="icon-user blue"></i>
                            <span class="blue">账号</span>
                        </div>
                        <div class="span10 text-success">
                            <?php echo ($users["name"]); ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row-fluid">
                        <div class="span2 mt-text">
                            <i class="icon-fire red"></i>
                            <span class="red">角色信息</span>
                        </div>
                        <div class="span10 text-success">
                            <?php echo ($roles); ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row-fluid">
                        <div class="span2 mt-text">
                            <i class="icon-envelope-alt blue"></i>
                            <span class="blue">邮箱</span>
                        </div>
                        <div class="span10 text-success">
                            <?php echo ($user["email"]); ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row-fluid">
                        <div class="span2 mt-text">
                            <i class="icon-picture blue"></i>
                            <span class="blue">头像</span>
                        </div>
                        <div class="span10" id="handlefile">
                            <img src="<?php echo ($users["face"]); ?>" class="file-select" style="width:160px; height:160px;">
                            <p class="red"> ( * 点击图片更换头像 ) </p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row-fluid">
                        <div class="span2 mt-text">
                            <i class="icon-time red"></i>
                            <span class="red">注册时间</span>
                        </div>
                        <div class="span10 me-time">
                            <?php echo ($user["create_time"]); ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row-fluid">
                        <div class="span2 mt-text">
                            <i class="icon-calendar blue"></i>
                            <span class="blue">上次登录时间</span>
                        </div>
                        <div class="span10 me-time">
                            <?php echo ($user["last_time"]); ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row-fluid">
                        <div class="span2 mt-text">
                            <i class="icon-coffee blue"></i>
                            <span class="blue">上次登录IP</span>
                        </div>
                        <div class="span10">
                            <?php echo ($user["last_ip"]); ?>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="tab-pane" id="info">
            <form class="form-horizontal" name="updatePass" id="updatePass">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label"> 原密码 </label>
                        <div class="controls">
                            <input type="password" name="oldPassword" required="true" rangelength="[2, 20]"/>
                            <p class="help-block text-danger">需要填写之前的密码 ! </p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"> 新密码 </label>
                        <div class="controls">
                            <input type="password" name="newPassword" required="true" rangelength="[2, 20]" neqTo="input[name=oldPassword]" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"> 确认新密码 </label>
                        <div class="controls">
                            <input type="password" name="truePassword" required="true" rangelength="[2, 20]" equalTo="input[name=newPassword]"/>
                            <p class="help-block text-danger"> 需要填写和新密码一致 ! </p>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">保存</button>
                        <button class="btn" type="reset">重置</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $("#myTab a:first").tab("show");$("#myTab a").click(function(b){b.preventDefault();$(this).tab("show")});

        $('.me-time').each(function(){$(this).html(timeFormat(parseInt($(this).html()), "yyyy-MM-dd hh:mm:ss"))});

        // 文件上传
        var uploader = MeUpload('#handlefile', function(file, data){
            $('#handlefile img').attr('src', data.sPath);
            CutOutImage('#handlefile', file.id, data.sPath, [160, 160]);
        });
        uploader.init();

        // 取消图片裁剪
        $(document).on('click', '.me-cancel', function(){
            $(this).parentsUntil('.alert-info').slideUp(1000, function(){
                $(this).parent().parent().parent().remove().empty();
            });
        });

        // 图片裁剪处理
        $(document).on('click', '.me-clipping', function(){
            var f = $($(this).attr('form')),
                d = f.find('input');
            if (d.val() && ((d.attr('x') || d.attr('y')) && (d.attr('w') || d.attr('h') )))
            {
                var l = layer.load();
                $.ajax({
                    type:       "POST",
                    url:        f.attr('action'),
                    data:       {
                        x:      d.attr('x'), // 883842500055260387
                        y:      d.attr('y'),
                        w:      d.attr('w'),
                        h:      d.attr('h'),
                        path:   d.val()
                    },
                    dataType:   "json",
                }).always(function(){
                    layer.close(l)
                }).done(function(json){
                    if (json.status == 1)
                    {
                        $('#handlefile .alert-info').slideUp(1000, function() {
                            $(this).parent().remove().empty();
                            $('#handlefile img').attr('src', $('#handlefile img').attr('src') + '?time=' + Math.random());
                        });
                    } else {
                        $.gritter.add({
                            title:  "图片裁剪出现问题：",
                            text:   json.msg,
                            image:  "/Public/img/avatar.jpg",
                            sticky: false,
                            time:   "",
                        });
                    }

                }).fail(ajaxFail)
            } else {
                layer.msg('需要确定裁剪位置');
            }

            return false;
        });

        // 默认选择
        if (/#info/.test(window.location.href))    setTimeout(function(){$('#password-a').trigger('click')}, 100);
        if (/#message/.test(window.location.href)) setTimeout(function(){$('#message-a').trigger('click');}, 100);

        $('.user-info').click(function(){$('#message-a').trigger('click')});
        $('.user-password').click(function(){$('#password-a').trigger('click')});

        // 修改密码
        $('#updatePass').submit(function(){
            if ($(this).validate().form())
            {
                oLoading = layer.load();
                $.ajax({
                    url:        'updatePassword.html',
                    type:       'POST',
                    data:       $(this).serialize(),
                    dataType:   'json',
                }).always(alwaysClose)
                .done(function(json){
                    gAlert('管理员信息', json.msg, json.status == 1 ? 'success' : 'warning' );
                    if (json.status == 1) $('#myTab a:first').addClass('active').trigger('click');
                    if (json.status == 3) window.location.href = '/admin';
                })
                .fail(ajaxFail);
            }
            return false;
        })
    });
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