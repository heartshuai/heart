<?php
namespace libs;

class Validate{
	public static $validates=[
		'required'=>'_empty',
		'string'=>'_string',
	];
	public static $errorMsg=[
		'required'=>'字段值不能为空',
		'string'=>'字段值长度不符合',

	];
	public static function _empty($field,$params=[]){
		return !empty($field);
	}
	public static function _string($field,$params=[]){
		$min=isset($params['min']) ? intval($params['min']) : 0;
		$max=isset($params['max']) ? intval($params['max']) : 1000000;
		$len=mb_strlen($field);
		if($len<$min || $len>$max) return false;
		return true;
	}
	public static function _email($field,$params=[]){
		return filter_var($field,FILTER_VALIDATE_EMAIL);
	}
}