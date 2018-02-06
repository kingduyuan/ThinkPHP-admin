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
                <style type="text/css">
    .me-label {display:inline-block;margin:5px 8px;min-width:100px}
    .me-label input,.me-label select {width:auto;}
    .me-label-sp {padding:5px 8px;}
</style>
<script type="text/javascript" src="/Public/js/wizard.min.js"></script>
<div class="box-header">
    <h2><i class="icon-magic"></i> 模块自动生成 </h2>
    <div class="box-icon">
        <a href="#" class="btn-setting"><i class="icon-wrench"></i></a>
        <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
        <a href="#" class="btn-close"><i class="icon-remove"></i></a>
    </div>
</div>
<div class="box-content">
    <div id="MeWizard" class="wizard">
        <ul class="steps">
            <li data-target="#step1" class="active"><span class="badge badge-info">1</span></li>
            <li data-target="#step2"><span class="badge">2</span></li>
            <li data-target="#step3"><span class="badge">3</span></li>
        </ul>
        <div class="actions">
            <button type="button" class="btn btn-prev">
                <i class="icon-arrow-left"></i> 上一步
            </button>
            <button type="button" class="btn btn-success btn-next" data-last="提交生成">
                下一步 <i class="icon-arrow-right"></i>
            </button>
        </div>
    </div>
    <div class="step-content">
        <div class="step-pane active" id="step1">
            <form class="form-horizontal" action="/admin/module/create.html" method="POST">
            <fieldset>
                <div class="control-group warning">
                    <label class="control-label" for="me-title"> 标题名称 </label>
                    <div class="controls">
                        <input type="text" name="title" id="me-title" required="true" rangelength="[2, 10]"/>
                        <span class="help-inline"> ( * 标题、权限、导航都基于该字段生成说明) </span>
                    </div>
                </div>
                <div class="control-group warning">
                    <label class="control-label" for="me-table">数据库表名</label>
                    <div class="controls">
                        <input type="text" name="table" id="me-table" required="true" rangelength="[2, 10]"/>
                        <span class="help-inline"> ( * 控制器、模型、权限都基于该字段命名 )</span>
                    </div>
                </div>
            </fieldset>
            </form>
        </div>
        <div class="step-pane" id="step2">
            <form class="form-horizontal" action="/admin/module/update.html" method="POST">
                <fieldset id="my-content">

                </fieldset>
            </form>
        </div>
        <div class="step-pane" id="step3">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>温馨提醒 ! </strong> 确认生成文件没有问题
            </div>
            <form class="form-horizontal produce" action="/admin/module/produce.html" method="POST">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label" for="input-html">HTML文件</label>
                        <div class="controls">
                            <input type="text" id="input-html" name="html" required="true" rangelength="[2, 12]" />
                            <label class="m_error"></label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="input-controller" required="true" rangelength="[2, 30]">控制器(Controller)</label>
                        <div class="controls">
                            <input type="text" id="input-controller" name="php" />
                            <label class="m_error"></label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"> 导航栏目 </label>
                        <div class="controls">
                            <label class="radio">
                                <input type="radio" name="menu" checked="true" value="1" /> 生成
                            </label>
                            <label class="radio">
                                <input type="radio" name="menu"  value="0" /> 不生成
                            </label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"> 权限操作 </label>
                        <div class="controls">
                            <label class="radio">
                                <input type="radio" name="auth" checked="true" value="1" /> 生成
                            </label>
                            <label class="radio">
                                <input type="radio" name="auth"  value="0" /> 不生成
                            </label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"> 允许文件覆盖 </label>
                        <div class="controls">
                            <label class="radio">
                                <input type="radio" name="allow" checked="true" value="0" /> 不允许
                            </label>
                            <label class="radio">
                                <input type="radio" name="allow"  value="1" /> 允许
                            </label>
                        </div>
                    </div>
                </fieldset>
                <div class="controls">
                    <label class="checkbox inline">
                        <input type="checkbox" id="inlineCheckbox2" name="isTrue" value="1" required="true" /> 确认生成
                    </label>
                </div>
            </form>
            <div class="alert alert-success">
                <div class="code"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var file       = null,
        controller = null;
    $(function(){
        $("#MeWizard").wizard().on('stepclick change', function(event, data) {
            if (data.direction === 'next')
            {
                var f = $('#step' + data.step +' form');
                if (f.validate(validatorError).form()) {
                    oLoading = layer.load();
                    $.ajax({
                        'async':        false,
                        'url' :         f.attr('action'),
                        'data' :        $('form').serialize(),
                        'type' :        'POST',
                        'dataType' :    'json',
                    }).done(function(json){
                        layer.msg(json.msg, {icon:json.status == 1 ? 6 : 5});
                        if (json.status == 1)
                        {
                            // 第一步提交
                            if (data.step === 1) $('#my-content').html(json.data);
                            // 第二步提交
                            if (data.step === 2)
                            {
                                $('.code').html(json.data.html);
                                // HTML
                                $('#input-html').val(json.data.file[0]);
                                if (json.data.file[1] == true)
                                {
                                    file = json.data.file[0]
                                    $('#input-html').next().html(' ( * 文件已经存在,需要重新定义文件名 )');
                                }

                                // Controller
                                $('#input-controller').val(json.data.controller[0]);
                                if (json.data.controller[1] == true)
                                {
                                    controller = json.data.controller[0]
                                    $('#input-controller').next().html(' ( * 文件已经存在,需要重新定义文件名 )');
                                }
                            }
                            return true;
                        } else {
                            event.preventDefault()
                        }
                    })
                    .fail(ajaxFail)
                    .always(alwaysClose);
                } else {
                    return false;
                }
            }
        }).on('finished', function(){
            // 初始验证
            if ($('.produce').validate(validatorError).form())
            {
                // 自己验证
                if ($('input[name=allow]:checked').val() == 1 || ($('#input-html').val() != file && $('#input-controller').val() != controller))
                {
                    var l = layer.load();
                    $.ajax({
                        url: "/admin/module/produce.html",
                        data: $('form').serialize(),
                        dataType: "json",
                        type: "POST",
                    }).done(function(json){
                        layer.msg(json.msg, {icon:json.status == 1 ? 6 : 5});
                        if (json.status == 1)
                        {
                            if ($('input[name=menu]:checked').val() == 1)
                                window.location.href = json.data;
                            else
                                window.location.reload();
                            $('form').each(function(){this.reset()});
                        }
                    }).fail(ajaxFail).always(function(){
                        layer.close(l);
                    })
                } else {
                    layer.msg('文件名存在, 不能执行覆盖操作...');
                }
            }
        });

        // 表单编辑的显示与隐藏
        $(document).on('change', '.is-hide', function(){
           if ($(this).val() == 0)
               $(this).next('select').hide().next('input').hide();
           else
               $(this).next('select').show().next('input').show();
        });
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