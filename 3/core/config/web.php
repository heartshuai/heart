<?php

return[
	'app_name'=>'newmvc',
	'version'=>'1.0 beta',
	'redirect_tpl'=>dirname(__DIR__).DS.'tpl'.DS.'redirect.html',
	'tpl_expire_time'=>0,
	'filter_var'=>'trim',


	'url_mode'=>1,  //0普通模式  1表示phpinfo模式
	'template_parse'=>[
		'JS_PATH'=>ASSERT_PATH.'/js',
		'CSS_PATH'=>ASSERT_PATH.'/css',
		'IMG_PATH'=>ASSERT_PATH.'/image',
	],
	//模板解析有效期
	'tpl_expire_time'=>0,
	//是否开启静态缓存
	'html_cache'=>true,
	//静态缓存时间
	'html_expire'=>3600,
	//配置缓存存储方式
	'cache_storage'=>'file', //file memcache redis
];

