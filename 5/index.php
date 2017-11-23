<?php
/*
入口文件
加载函数库
启动框架
 */
define('IMOOC',realpath(' /'));
define('core',IMOOC.'/core');
define('APP',IMOOC,'/app');
define('DEBUG',true);
if(DEBUG){
	ini_set('display_error', 'on');
}else{
	ini_set('display_error','off');
}
include CORE.'/common/function.php';
p(IMOOC);