<?php
namespace libs\cache;

interface Icache
{
	public function set($key,$data);
	public function get($key);
	public function flush($key='');
}