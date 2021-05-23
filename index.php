<?php
// 应用目录为当前目录
const APP_PATH = __DIR__ . '/';
// 开启调试模式
const APP_DEBUG = true;
const APP_HEADER = APP_PATH . 'app/views/head.php';
const APP_FOOTER = APP_PATH . 'app/views/footer.php';
//你的域名.comm 是你的服务器域名
const SITE_ROOT = 'http://store.xinball.top';
// 加载框架文件
require(APP_PATH . 'xbphp/Xbphp.php');
require(APP_PATH . 'library/RedisConnect.php');
require(APP_PATH . 'library/Pager.php');
// 加载配置文件
$config = require(APP_PATH . 'config/config.php');

// 实例化框架类
(new xbphp\Xbphp($config))->run();
?>