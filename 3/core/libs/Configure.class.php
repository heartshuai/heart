<?php
namespace libs;

class Configure
{
	public static $config=[];

	public static function loadConfig()
	{
		//1.读取核心配置目录中的所有配置文件
		$sys_config=self::getConfigFromDir(dirname(__DIR__).DS.'config');
		//2.读取应用目录下配置目录的配置文件
		$app_config = self::getConfigFromDir(CONFIG_PATH);
		self::$config = array_merge($sys_config,$app_config);
	}
	public static function getConfigFromDir($dir=__DIR__){
		$config = [];
		foreach(glob($dir.DS."*.php") as $filename){
				$values= include $filename;
				if(is_array($values)){
					$config = array_merge($config,$values);
				}
							
		}
		return $config;
		// echo $dir;
		
	}

	public static function config($name,$value=''){
		if($value==''){
			if(isset(self::$config[$name])){
				return self::$config[$name];
			}else{
				return null;
			}
		}else{
			self::$config[$name] = $value;
		}
	}
}