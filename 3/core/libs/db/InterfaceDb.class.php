<?php

namespace libs\db;

interface interfaceDb
{

	//数据库连接
	public function connect($config=[]);

	//查询
	public function select($sql='');

	// 新增
	public function insert($sql='');

	//删除
	public function delete($sql='');

	//修改
	public function update($sql='');
}