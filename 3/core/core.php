<?php
//第一步 设置应用目录
if(!defined("APP_PATH"))define("APP_PATH",dirname(__DIR__));
if(!defined('DS'))define('DS','/');
if(!defined("CONTROLLER_PATH"))define("CONTROLLER_PATH",APP_PATH.DIRECTORY_SEPARATOR.'controllers');
if(!defined("MODEL_PATH"))define("MODEL_PATH",APP_PATH.DIRECTORY_SEPARATOR.'models');
if(!defined("VIEW_PATH"))define("VIEW_PATH",APP_PATH.DIRECTORY_SEPARATOR.'views');
if(!defined("RUNTIME_PATH"))define("RUNTIME_PATH",APP_PATH.DIRECTORY_SEPARATOR.'runtime');
//存放模板引擎的解析文件
defined("CACHE_DIR") or define('CACHE_DIR',RUNTIME_PATH.DS.'cache');
//存放数据缓存文件
defined("DATA_DIR") or define('DATA_DIR',RUNTIME_PATH.DS.'data');
//存放日志文件
defined("LOG_DIR") or define('LOG_DIR',RUNTIME_PATH.DS.'logs');
//存放静态文件存储
defined("HTML_DIR") or define('HTML_DIR',RUNTIME_PATH.DS.'html');
if(!defined("CONFIG_PATH"))define("CONFIG_PATH",APP_PATH.DIRECTORY_SEPARATOR.'config');
if(!defined("FUNCTION_PATH"))define("FUNCTION_PATH",APP_PATH.DS.'functions');
if(!defined("PHP_EXT")) define("PHP_EXT",".class.php");
defined("AB_ROOT") or define('AB_ROOT',$_SERVER['DOCUMENT_ROOT']);
if (!defined("CORE_PATH")) define("CORE_PATH",__DIR__);
defined("_ROOT_") or define('_ROOT_',str_replace(AB_ROOT,'',str_replace('\\','/',APP_PATH)));
defined('ASSERT_PATH') or define('ASSERT_PATH',_ROOT_.'/assets');
if(phpversion()<'5.0.0'){
	exit ("您的php版本不支持");
}

if(function_exists("ini_set")){
	ini_set('display_errors','off');
}
$GLOBALS['beginTime']=time();
$GLOBALS['phpversion']=phpversion();
$GLOBALS['env']=php_sapi_name();

// if(!isset($GLOBALS['$redirect_tpl'])) $GLOBALS['$redirect_tpl'] = __DIR__.DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'redirect.html';

require "libs/Application.class.php";
class App extends \app\Application
{
	
}