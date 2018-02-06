<?php
/**
 * Created by PhpStorm.
 * User: liujinxing
 * Date: 2016/7/5
 * Time: 11:19
 */
header('Content-Type: text/html; charset=utf-8');
error_reporting(0);

// 获取 post 提交数据
function post($name)
{
    $data = isset($_POST[$name]) ? $_POST[$name] : null;
    if (!empty($data) && is_string($data)) $data = trim($data);
    return $data;
}

// 判断提交数据
if (! empty($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
    // 接收参数
    $host     = post('host');     // 数据库地址
    $username = post('user');     // 数据库用户名
    $password = post('pass');     // 数据库密码
    $database = post('database'); // 数据库
    $prefix   = post('prefix');   // 表前缀

    $arrError = [
        'status' => 0,
        'msg'    => '提交参数存在问题, 请确认填写完成',
    ];

    // 验证数据的有性
    if ($host && $username && $password && $database)
    {
        $prefix = $prefix ? $prefix : 'my_';
        // 验证数据库名称只能小写字母加下划线
        $arrError['msg'] = '数据库名称只能小写字母加下划线';
        if (preg_match('/^[a-z]{1,}[a-z_]{1,}$/', $database))
        {
            // 开始连接数据库
            $mysql = new mysqli($host, $username, $password);
            $arrError['msg'] = '数据库连接出现问题 Error:' . $mysql->connect_error;
            if ($mysql->connect_errno == 0)
            {
                // 设置字符串
                $mysql->query('SET NAMES UTF8');

                // 选择库,没有存在执行新建库
                $mysql->select_db($database);
                if ($mysql->errno)
                {
                    $mysql->query('CREATE DATABASE `'.$database.'`');
                    $mysql->select_db($database);
                }

                // 没有错误
                $arrError['msg'] = '数据库操作出现问题 Error:' . $mysql->error;
                if (empty($mysql->errno))
                {
                    // 检查表信息
                    $result   = $mysql->query('SHOW TABLES');
                    $arrTable = [$prefix.'admin', $prefix.'auth_child', $prefix.'auth_item', $prefix.'menu'];
                    $strError = '';
                    if ($result)
                    {
                        while ($row = $result->fetch_row()) {
                            if (in_array($row[0], $arrTable)) $strError .= ' 数据表('.$row[0].')已经存在; ';
                        }

                        $result->free();
                    }

                    // 没有错误
                    $arrError['msg'] = $strError;
                    if (empty($strError))
                    {
                        // 执行数据库操作
                        $mysql->multi_query(str_replace('my_', $prefix, file_get_contents('./project.sql')));
                        do {
                            $result = $mysql->store_result();
                            if ($result) $result->free();
                        } while ($mysql->next_result());
                        if ($mysql->error == 0)
                        {
                            // 信息返回
                            $arrError = [
                                'status' => 1,
                                'msg'    => '安装成功, 为你跳转到后台登录页面'
                            ];
                            $config = <<<PHP
<?php
return [
	// 路由规则
    'URL_ROUTE_RULES' => [],
    'VAR_PAGE'        => 'page',                    // 分页信息提交参数
    // 前端资源配置
    'TMPL_PARSE_STRING' => [
        '__PUBLIC__'  => '/Public',                 // 更改默认的/Public 替换规则
        '__CSS__'     => '/Public/assets/css',      // css文件地址
        '__JS__'      => '/Public/assets/js',       // 增加新的JS类库路径替换规则
        '__UPLOAD__'  => '/Public/Uploads',         // 增加新的上传路径替换规则
    ],
    // 开启布局
    'LAYOUT_ON'         => true,
    'LAYOUT_NAME'       => 'Layout/main',
    'TMPL_LAYOUT_ITEM'  => '{__CONTENT__}',
    'DATA_CACHE_TYPE'   => 'Kvdbsae',
    'DATA_CACHE_PREFIX' => 'wx_',
    'DATA_CACHE_TIME'   => 0,
    // 数据库设置
    'DB_TYPE'               => 'mysql',                // 数据库类型
    'DB_HOST'               => '{$host}',              // 服务器地址
    'DB_PORT'               => 3306,                   // 服务器端口
    'DB_NAME'               => '{$database}',          // 数据库名
    'DB_USER'               => '{$username}',          // 用户名
    'DB_PWD'                => '{$password}',          // 密码
    'DB_PREFIX'             => '{$prefix}',            // 数据库表前缀
];
PHP;
                            // 写入配置文件
                            file_put_contents('./Application/Common/Conf/config.php', $config);

                            $date  = date('Y-m-d H:i:s');
                            $index = <<<PHP
<?php
/**
 * file: index.php
 * desc: 使用ThinkPHP 3.2.3 入口文件
 * user: liujx
 * date: {$date}
 */
header('Content-Type:text/html; charset=utf-8');                             // 应用入口文件
if (version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !'); // 检测PHP环境
define('APP_DEBUG', true);                                                   // 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_PATH','./Application/');                                         // 定义应用目录
require './ThinkPHP/ThinkPHP.php';                                           // 引入ThinkPHP入口文件
PHP;

                            // 写入目录文件
                            file_put_contents('./index.php', $index);

                            // 处理安装文件
                            rename('./install.php', './install.log');
                        }
                    }
                }
            }
        }
    }

    exit(json_encode($arrError));
}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>后台管理系统安装</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />

    <!--移动优先-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--引入公共CSS文件-->
    <link rel="stylesheet" href="/Public/Home/css/bootstrap.min.css" />

    <!--引入公共js文件-->
    <script type="text/javascript" src="/Public/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/Public/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/Public/js/validate.message.js"></script>
    <script type="text/javascript" src="/Public/js/layer/layer.js"></script>
    <style type="text/css">
        div.main {margin-top:70px;}.error {color:red}
    </style>
</head>
<body>
    <div class="container theme-showcase main" role="main">
        <div class="row">
            <div class="col-md-12">
                <h1>后台管理系统安装 <button class="btn btn-info" onclick="$('#myModal').modal('show')">安装信息</button></h1>
                <form>
                    <div class="form-group">
                        <label>数据库地址</label>
                        <input type="text" class="form-control" name="host" required="true" rangelength="[2, 20]" value="127.0.0.1" placeholder="database name">
                    </div>

                    <div class="form-group">
                        <label>数据库用户名</label>
                        <input type="text" class="form-control"  name="user" required="true" rangelength="[2, 20]" placeholder="database user"  value="root" />
                    </div>
                    <div class="form-group">
                        <label>数据库密码</label>
                        <input type="password" class="form-control"  name="pass" required="true" rangelength="[2, 40]" placeholder="database Password">
                    </div>
                    <div class="form-group">
                        <label>数据库名</label>
                        <input type="text" class="form-control" name="database" required="true" rangelength="[2, 20]" placeholder="database name">
                    </div>
                    <div class="form-group">
                        <label>数据表前缀</label>
                        <input type="text" class="form-control"  name="prefix" placeholder="database table prefix" value="my_"  >
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="check" required="true"> 我同意
                        </label>
                    </div>
                    <button type="submit" class="btn btn-success">提交</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">温馨提醒</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <p> 后台项目位于./Application/Admin </p>
                        <p> 超级管理员账号：<strong class="text-success"> admin </strong> </p>
                        <p> 超级管理员密码：<strong class="text-danger"> admin123 </strong> </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="close-modal">好的, 我知道了</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function(){
            // 关闭modal
            $('#close-modal').click(function(){$('#myModal').modal('hide')});

            // 表单提交
            $('form').submit(function(){
                if ($(this).validate({
                        errorPlacement:function(error, errorPlacement) {
                            error.appendTo(errorPlacement.parent().addClass('has-error'));
                        },
                        success:function(label){
                            label.parent().removeClass('has-error');
                        }
                    }).form()){

                    // 数据请求
                    var l = layer.load();
                    $.ajax({
                        url:  '/install.php',
                        type: 'POST',
                        data: $('form').serialize(),
                        dataType:'json',
                    }).done(function(json){
                        layer.msg(json.msg, {icon:json.status == 1 ? 6 : 5, end:function(){
                            if (json.status == 1) $('#myModal').modal('show');
                        }})
                    }).fail(function(){
                        layer.msg('服务器繁忙, 请求稍候再试...');
                    }).always(function(){
                        layer.close(l);
                    })
                }
                return false;
            })
        })
    </script>
</body>
</html>