<?php
namespace libs;
//路由类
//r=index/index&_GET
//index/index/a/1/b/2 PATHINFO模式

class Route
{
	private $_controller_='index';
	private $_method_='index';
	private static $instance;
	public function __construct(){
		if(isset($_SERVER['PATH_INFO']) && C('url_mode')==1){
			$path = explode('/',$_SERVER['PATH_INFO']);
			if(isset($path[1]) && !empty($path[1])){
				$this->_controller_=$path[1];
			}
			if(isset($path[2]) && !empty($path[2])){
				$this->_method_=$path[2];
			}

			//参数
			for($i=3,$len=count($path);$i<$len;$i+=2){
				if(isset($path[$i]) && !empty($path[$i]) && isset($path[$i+1]) && !empty($path[$i+1])){
					$_GET[$path[$i]] = $path[$i+1];

				}
			}
		}elseif(isset($_GET['r'])){ 
			$entity = $_GET['r'];
			list($this->_controller_,$this->_method_)=explode('/',$entity);
		}
		$this->_controller_=ucfirst($this->_controller_);
		$this->_method_=ucfirst($this->_method_);

		define('_CONTROLLER_',$this->_controller_);
		define('_METHOD_',$this->_method_);
	}
	public static function getInstance(){
		if(!self::$instance instanceof self){
			self::$instance = new self;
		}
		return self::$instance;
	}
	public static function getRoute(){
		$instance=self::getInstance();
		//加上我实际的命名空间
		$namespace=str_replace(APP_PATH, '', CONTROLLER_PATH);
		$namespace=str_replace(DIRECTORY_SEPARATOR,'\\',$namespace);
		$namespace.='\\';

		$controller=$namespace._CONTROLLER_."Controller";
		$method="action"._METHOD_;
		return ['controller'=>$controller,'method'=>$method];
	}	
}