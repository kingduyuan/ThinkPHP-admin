<?php
/**
 * file: index.php
 * desc: 使用ThinkPHP 3.2.3 入口文件
 * user: liujx
 * date: 2017-01-02 02:41:38
 */
header('Content-Type:text/html; charset=utf-8');                             // 应用入口文件
if (version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !'); // 检测PHP环境
define('APP_DEBUG', true);                                                   // 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_PATH','./Application/');                                         // 定义应用目录
require './ThinkPHP/ThinkPHP.php';                                           // 引入ThinkPHP入口文件