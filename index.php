<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    die('require PHP > 5.3.0 !');
}

// 定义应用目录
define('APP_PATH', './App/');
define('RUNTIME_PATH', './Cache/');
define('APP_DEBUG', true);

//获取当前网站域名路径
$url = $_SERVER['REQUEST_SCHEME'] . '://';
$url .= $_SERVER['SERVER_NAME'] . '/';
$url .= dirname($_SERVER['SCRIPT_NAME']);
$url = rtrim($url, '');
define('SITE_URL', $url);

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';
