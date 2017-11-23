<?php
/*
控制器基类
实现几个基本方法
 */
namespace libs;

class Controller extends \libs\Base
{	
	const BEFORE_ACTION='beforeAction';
	const BEFORE_EDIT='beforeEdit';
	public $view;

	public function __construct(){
		if(method_exists($this, 'init')){
			$this->init();
		}
		//构造一个view
		$this->view=new View;
	}
	public function assign($arr=[]){
		$this->view->assign($arr);

	}
	public function render($templateFile='',$params=[]){
		//将$templateFile 以及$params 传递给 $view 对象，让视图渲染
		return $this->view->display($templateFile,$params);
	}
	public function redirect($url,$param=[]){
		if($param){
			if(strrpos($url,'?') !== false){
				$url.='&'.http_build_query($param);
			}else{
				$url.='?'.http_build_query($param);
			}
		}
		
		header('Location:'.$url);
		return '跳转中';
		// header("Location:".$url);
	}
	public function go($message='',$url='',$params=[]){
		if($url==''){
			$url=$_SERVER['HTTP_REFERER'];
		}
		if($params){
			if(strrpos($url,'?')!==false){
				$url.='&'.http_build_query($param);
			}else{
				$url.='?'.http_build_query($param);
			}
		}
		// echo $url;die;
		return $this->render(C('redirect_tpl'),['url'=>$url,'message'=>$message]);
	}
	public function goBack(){
		header("Location:".$_SERVER['HTTP_REFERER']);
		return '';
	}
}

