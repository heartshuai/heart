<?php
namespace app;
// if(!defined('APP_NAME')) die ("deny access");
use libs\Configure;
use libs\Route;
use libs\Request;
use libs\Response;
use libs\Base;
use libs\exceptions\NFileException;
//应用类
class Application
{
	private static $instance;
	private static $namespaceMap=[];
	public static $app;
	private $_logger;

	public $state;
	const BEFORE_REQUEST ='beforeRequest';
	const After_REQUEST = 'afterRequest';
	const END_REQUEST = 'endRequest';
	private function __construct(){
		//设置异常接管
		set_exception_handler(array('\app\Application','exceptionHandler'));
		//设置错误接管
		set_error_handler(array('\app\Application','errorHandle'));
		//捕获致命错误
		register_shutdown_function(['\app\Application','handleFatalError']);
		//设置日期
		date_default_timezone_set("PRC");
		// 系统全局配置
		
		self::initMap();

		spl_autoload_register("self::_autoload");
		//加载配置文件    加载常量文件
		Configure::loadConfig();
		// 加载函数文件
		self::loadFun();
		header("content-type:text/html;charset=utf-8");

	}

	public static function exceptionHandler($exception){ 
		$code=$exception->getCode();
		switch ($code) {
			case 3000:
				echo $exception->getMessage()."文件不存在";
				break;
		}
		die;
		// echo $exception->getMessage();
	}
	public static function errorHandle($errno,$errmsg,$errfile,$errline){
		throw new \Exception($errmsg,$errno);
	}
	public static function handleFatalError(){
		$error=error_get_last();
		//var_dump($error);
		if(isset($error['type']) && in_array($error['type'],[E_ERROR,E_PARSE,E_CORE_ERROR,E_CORE_WARNING,E_COMPILE_ERROR,E_COMPILE_WARNING])){
			//flush();
			echo '致命错误';
			exit(1);
		}
	}
	private static function loadFun(){
		//加载系统
		foreach(glob(dirname(__DIR__).DS.'func'.DS."*.php") as $filename){
			include $filename;
		}
		//加载应用
		foreach(glob(FUNCTION_PATH.DS."*.php") as $filename){
			include $filename;
		}
	}
	private function __clone(){

	}
	public static function getInstance(){
	if(!self::$instance instanceof Application){
		self::$instance = new self();
		}
		return self::$instance;
	}
	//实现自动加载
	public static function _autoload($className){
		$className=str_replace('\\','/',$className);
		// var_dump($className);die;
		$namespace=substr($className,0,strrpos($className,'/'));
		// var_dump($className);die;
		if(array_key_exists($namespace,self::$namespaceMap)){
			$className=str_replace($namespace,self::$namespaceMap[$namespace],$className);
		}
		// var_dump($className);die;
		$className.=PHP_EXT;//文件后缀
		if(!file_exists($className)){
			throw new \Exception('123');
			// 
		}
		// echo $className;
		// echo "<br>";
		include_once $className;
	}
	// public static function initMap(){
		// var_dump(dirname(__DIR).'/'.basename(__DIR__));die;
		// self::$namespaceMap=[
		// 'libs'=>dirname(__DIR__).'/'.basename(__DIR__),
		// 'libs/db'=>dirname(__DIR__).'/'.basename(__DIR__).'/db',
		// 
		// ];
	// }
	public  static function initMap(){
		// var_dump(dirname(__DIR__).'/'.basename(__DIR__));die;
		self::$namespaceMap = include CORE_PATH.DS.'classMap.php';
	} 
	public static function setNamespaceMap($arrNamespace){
		self::$namespaceMap += $arrNamespace;
	}
	public static function start(){
		$application=self::getInstance();

		// var_dump($a);die;
		//交给路由处理[Route.class.php]
		$route=Route::getRoute();

		// self::$state = self::BEFORE_REQUEST;
		// $application->trigger(self::BEFORE_REQUEST);

		// new Request();
		//参数获取类
		
		new Request();
		extract($route);
		try {
			$obj = new $controller;
		}catch (NFileException $e) {
			echo $e->getMessage();
		}
		// var_dump($route['method']);die;
		//得到控制器和方法 执行得到方法[IndexController.php]
		//错误处理
		//
		
		
		$response = (new $controller)->$method();
		// self::$state = self::After_REQUEST;
		// $application->trigger(self::After_REQUEST);

		Response::send($response);
		// $controller=libs\BaseTree::get($controller);
		// $response=$controller->$method();
		// self::$state = self::END_REQUEST;
		// $application->trigger(self::END_REQUEST);

		//返回【Response】
		// echo $response;
	}
}


