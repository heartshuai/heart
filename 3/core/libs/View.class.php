<?php

namespace libs;

class View extends \libs\Base
{
	public $layout='';
	public $values=[];
	public function assign($arr=[]){
		$this->values=array_merge($this->values,$arr);
	}
	public function display($template='',$params=[]){
		if(is_array($params)){
			$this->assign($params);
		}
		extract($this->values);
		$filename= $template;
		if($template==''){
			$template = _CONTROLLER_.DIRECTORY_SEPARATOR._METHOD_.'.html';
			$filename = VIEW_PATH.DIRECTORY_SEPARATOR.$template;
			// 这是最终的路径
		}elseif(strrpos($template,DIRECTORY_SEPARATOR) === false){
			$template = _CONTROLLER_.DIRECTORY_SEPARATOR.$template.'.html';
			$filename = VIEW_PATH.DIRECTORY_SEPARATOR.$template;
		}
		//这是最终的路径
		
		

		
		//将content写入咱们的runtime目录中确定一个唯一的名字
		$runtime_filename = CACHE_DIR.DS.md5($filename);
        $html_filename = HTML_DIR.DS.md5($filename);
        //判断是否开启类静态缓存
        if (C('html_cache') && file_exists($html_filename) && time()-filetime($html_filename) < C('html_expire')) {
            return file_get_contents($html_filename);
        }
		if(!file_exists($runtime_filename) || time()-filemtime($runtime_filename) > C('tpl_expire_time')){
				
				$content = file_get_contents($filename);
				$content=ParseTemplate::parse($content);
				file_put_contents($runtime_filename, $content);
		}
		// 将文件包含进来
		ob_start();
		// echo $filename;die;
		include $runtime_filename;
		// 将结果执行
		$result=ob_get_contents();
		ob_end_clean();


		// 返回结果
		return $result;

	}
}