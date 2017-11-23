<?php

function C($name,$value=''){
	return libs\Configure::config($name,$value);
}
//创建模型
function M($name){
	$objname = str_replace(APP_PATH,'',MODEL_PATH);
	$objname = str_replace('/','\\',$objname).'\\'.$name;
	return libs\BaseTree::get($objname);
}
function I($name=''){
	//使用指定的方式进行数据的过滤操作
	$data=libs\Request::$data;
	if(empty($name) && isset($data[$name])){
		return $data[$name];

	}else{
		return $data;
	}
	
	
	
}

function U($url,$params=[]){
	$url_mode = C('url_mode');
	$controller='';
	$action='';
	$url = explode('/',$url);
	// echo $url;die;
	if(count($url)==1){
		$action=$url[0];
		$controller=_CONTROLLER_;
	}elseif(count($url)==2){
		$action = $url[1];
		$controller =$url[0];
	}
	if($url_mode == 0){
		$createUrl =_ROOT_.'/index.php?r='.$controller.'/'.$action;
	}elseif($url_mode == 1){
		$createUrl =_ROOT_.'/index.php/'.$controller.'/'.$action;
	}
	$param='';
	//判断参数
	if($params){
		$sep='=';
		$link='&';
		if($url_mode == 1){$sep='/';$link='/';}
		$arr=[];
		foreach ($params as $key => $v) {
			$arr[]=$key.$sep.$v;
		}
		$param =$link.implode($link, $arr);
	}
	// echo $createUrl.$param;
	return $createUrl.$param;
}
function F($key,$data=''){
	$cache=new libs\Cache();
	$res=false;
	if($key && $data){
		$res=$cache->set($key,$data);
	}elseif($key){
		$res=$cache->get($key);
	}
	return $res;
}