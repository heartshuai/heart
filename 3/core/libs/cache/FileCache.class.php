<?php
namespace libs\cache;

use libs\cache\ICache;

class FileCache implements ICache
{
	public $cacheDir;
	public function __construct(){
		//判断存储目录是否存在
		$this->cacheDir=DATA_DIR;
		if(false==is_dir($this->cacheDir)){
			mkdir($this->cacheDir,0777,true);
		}
	}
	public function set($key,$data){
		//$key 文件名 $data 数据一般序列化后的数据
		$filename=$this->cacheDir.DS.$key.'.php';
		return file_put_contents($filename, serialize($data));
	}
	public function get($key){
		$data='';
 		//$key 就是文件名  根据文件名进行include 数据返回
 		$filename=$this->cacheDir.DS.$key.'.php';
 		if(file_exists($filename)){
 			$data=file_get_contents($filename);
 			$data=unserialize($data);
 		}
 		return $data;
	}
	public function flush($key=''){
		//如果$key为空表示删除文件  不为空就删除单个
		$filename=$this->cacheDir.DS.$key.'.php';
		if(file_exists($filename)){
			unlink($filename);
		}
	}
}