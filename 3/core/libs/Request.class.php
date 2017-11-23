<?php
namespace libs;

class Request{
	public static $data=[];
	public static $cookie = [];
	public static $headers = [];


	public function __construct(){
		self::getParams();
		self::getCookies();
		self::getHeader();
		$func = 'trim,'.C('filter_var');
		$func = explode(',', $func);
		foreach ($func as $method) {
			if($method){
				if(is_array($method)){
					self::$data = array_map($method, self::$data);
				}
				
			}
		
		}
	}
	//获取所有的参数
	public static function getParams(){
		if(isset($_GET)){
			self::$data = array_merge(self::$data,$_GET);
		}
		if(isset($_POST)){
			self::$data = array_merge(self::$data,$_POST);
		}
		if(!self::$data){
			self::$data = file_get_contents("php://input");
		}
		
	}
	public static function getCookies(){
		self::$cookie =$_COOKIE;
	}
	public static function getHeader(){
		if(function_exists("getallheaders")){
			self::$headers=getallheaders();
		}else{
			foreach($_SERVER as $key =>$value){
				if('HTTP_'==substr($key,0,5)){
					self::$headers[str_replace('_', '-', substr($key,5))]= $value;
				}
			}
		}
	}
	public static function getRemoteIp(){
		return $_SERVER['REMOTE_ADDR'];
	}	
	

}