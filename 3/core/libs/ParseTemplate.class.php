<?php
namespace libs;
//模板解析类

//将页面中的模板标签替换成php源码

class ParseTemplate
{
	public static function parse($content)
	{
		$pf=[
		'#\{\$([a-zA-z_][a-zA-z_0-9]*)\}#',
		'#_ROOT_#',
		'#\{:(.*?)}#'
		];

		$pt=[
			'<?=\$$1?>',
			'<?=_ROOT_?>',
			'<?=$1?>'
		];

		$content=preg_replace($pf, $pt, $content);

		$template_parse = C('template_parse');

		if($template_parse){
			//进行替换
			$content=str_replace(array_keys($template_parse), array_values($template_parse), $content);
		}
		return $content;
	}
}