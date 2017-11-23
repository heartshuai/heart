<?php

namespace models;

use libs\Model;

class StuModel extends Model
{
	public $tableName='test';
	
	public function rules(){
		return [
		// ['title','required'],
		// ['title','string','params'=>['min'=>6,'max'=>16]],
		// ['title','big'],
		];
	}

	// public function big($field,$params=[]){
		// if(intval($fiedld) <5) return false;
		// return true;
	// }
	public function beforeSave($obj){
		// var_dump($obj);die;
		// $obj->data->data['title'] = '事件测试';
		// file_put_contents('./t', "beforeSave");
	}

}