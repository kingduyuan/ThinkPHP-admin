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
                <!--前面导航信息-->
<div class="box-header" data-original-title="">
    <h2><i class="icon-desktop"></i><span class="break"></span></h2>
    <div class="box-icon">
        <a title="导出" class="me-table-export"><i class="icon-download-alt"></i></a>
        <a title="添加" href="#" class="me-table-insert"><i class="icon-plus"></i></a>
        <a class="btn-minimize" title="隐藏" href="#"><i class="icon-chevron-up"></i></a>
    </div>
</div>
<div class="box-content">
    <!--表格数据-->
    <table class="table table-striped table-bordered table-hover" id="showTable"></table>
</div>
<script type="text/javascript">
    var myTable = new MeTable({sTitle:"权限管理", sBaseUrl:"/admin/auth/update/type/2.html"},{
        "aoColumns":[
            {"data":"name", "sName":"name", "title":"权限名称", "edit":{"options":{"required":true,"rangelength":"[2, 50]"}}, "search":{"type":"text"}},
            {"data":"desc", "sName":"desc", "bSortable":false, "title":"说明信息", "edit":{"options":{"required":true,"rangelength":"[2, 255]"}}},
            {"data":"create_time", "sName":"create_time", "title":"创建时间", "createdCell":dateTimeString},
            {"data":"update_time", "sName":"update_time", "title":"修改时间", "createdCell":dateTimeString},
            oOperate
        ],

        // 设置隐藏和排序信息
        "order":[[2, "desc"]],
        // "columnDefs":[{"targets":[2,3], "visible":false}],
    });

    // 显示之前
    myTable.beforeShow = function(data, isData) {
        $(this.options.sFormId +' input[name=name]').attr('readonly', this.actionType !== 'insert');
        return true;
    };

    $(function(){
        myTable.init();
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