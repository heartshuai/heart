<?php
namespace libs;
use libs\cache\FileCache;
use libs\cache\MemCache;
class Cache
{
	public $storage;
	public $name;
	public $storageName;
	public function __construct($storageName=''){
		$this->storageName=$storageName ? $storageName : C('cache_storage');
		switch (strtolower($this->storageName)) {
			case 'file':
				$this->storage= new FileCache();
				break;
			case 'memcache':
				$this->storage=new MemCache();
				break;
			case 'redis':
				break;
		}
	}
	public function setName($name){
		$this->name=$name;//对于文件缓存而言  是文件名 对于memcache而言 键名
	}
	public function set($key,$data){
		if(empty($key))$key=$this->name;
		//如果storage 存储方式是文件的话 $key就是文件名，否则是键名
		return $this->storage->set($key,$data);
	}
	public function get($key){
		return $this->storage->get($key);
	}
	public function flush($key){
		$this->storage->flush($key);
	}
}