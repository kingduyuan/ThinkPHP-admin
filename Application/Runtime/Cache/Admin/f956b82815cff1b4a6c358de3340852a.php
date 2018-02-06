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
        <a title="导出" class="me-table-export">
            <i class="icon-download-alt"></i>
        </a>
        <a title="添加" class="me-table-insert">
            <i class="icon-plus"></i>
        </a>
        <a id="table-refresh" title="刷新" href="#" onclick="return myTable.search();">
            <i class="icon-refresh"></i>
        </a>
        <a class="btn-minimize" title="隐藏" href="#">
            <i class="icon-chevron-up"></i>
        </a>
    </div>
</div>
<div class="box-content">
    <!--表格数据-->
    <table class="table table-striped table-bordered table-hover" id="showTable"></table>
</div>
<link rel="stylesheet" type="text/css" href="/Public/Admin/js/jcrop/jquery.Jcrop.min.css" />
<script type="text/javascript" src="/Public/Admin/js/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/Public/Admin/js/plupload/zh_CN.js"></script>
<script type="text/javascript" src="/Public/Admin/js/jcrop/jquery.Jcrop.min.js"></script>
<script type="text/javascript">
    var roles    = <?php echo (json_encode($roles)); ?>,
        uploader = null,
        myTable  = new MeTable({sTitle:"管理员信息"},{
        "aoColumns":[
            {"data":"id", "title":"id", "sName":"id", "edit":{"type":"hidden"}, "search":{"type":"text"}},
            {"data":"username", "sName":"username","title":"管理员账号", "edit":{"options":{"required":1, "rangelength":"[2, 15]"}}},
            {"data":"password", "sName":"password", "title":"管理员密码","edit":{"type":"password", "options":{"rangelength":"[6, 20]"}}},
            {"data":"truepass", "sName":"truepass", "title":"确认密码","edit":{"type":"password", "options":{"rangelength":"[6, 20]", "equalTo":"input[name=password]:first"}}, "defaultContent":"", "isExport": false},
            {"data":"email", "sName":"email","title":"管理员Email", "edit":{"type":"text", "options":{"required":1, "rangelength":"[2, 50]", "email":1}}, "search":{"type":"text"}},
            {"data":"face", "sName":"face","title":"使用头像",
                "edit": {"type":"image", "options":{"required":1, "rangelength":"[2, 100]"}},
                "createdCell": function(td, data) {
                    $(td).html(data ? "<img style='width:30px;height:30px;' src='" + data + "'>" : "");
                }
            },
            {"data":"status", "sName":"status","title":"状态","value":{"1":"启用", "0":"待审核", "-1":"停用"}, "createdCell":function(td, data, rowdatas, row, col) {
                var md = {"-1" : {"class":"important", "title":"停用"}, "0" : {"class":"warning", "title":"待审核"}, "1" :{"class":"success", "title":"启用"}}, arr = md[data];
                $(td).html('<span class="label label-' + arr["class"] + '">' + arr["title"] + '</span>')
            }, "edit":{"type":"radio","default": 1, "options":{"required":1, "number":1}}, "search":{"type":"select"}},
            {"data":"roles", "sName":"roles", "bSortable":false, "title":"角色","value":roles, "edit":{"type":"checkbox", "default": 1, "options":{'isHave':true}}},
            {"data":"create_time", "sName":"create_time","title":"注册时间", "createdCell":dateTimeString},
            {"data":"last_time", "sName":"last_time", "title":"最后登录时间", "createdCell":dateTimeString},
            {"data":"last_ip", "sName":"last_ip", "bSortable":false, "title":"最后登录IP"},
            {"data": null, "title":"操作", "bSortable":false, "createdCell":setOperate, "width":"120px"}
        ],

        "columnDefs":[{"targets":[2,3], "visible":false}],
    });

    // 显示之后
    myTable.afterShow = function(data, isDetail) {
        if (this.actionType == 'update') $(this.options.sFormId + ' input[name=password]').val('');
        uploader.refresh();
        return true;
    };

    // 显示之前
    myTable.beforeShow = function(data, isDetail){
        uploader.refresh();
        $('#handleface .alert-success').remove().empty();
        $('#handleface .btn-success').attr('disabled', false);
        return true;
    };

    $(function(){
        myTable.init();
        uploader = MeUpload('#handleface');
        uploader.init();
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