<?php
namespace controllers;
use libs\Controller;
use models\StuModel;
use libs\Configure;
use libs\Response;
use libs\Request;
use libs\Cache;
class IndexController extends Controller
{   private $arr = [];
	const AFTER_LOGIN='afterLogin';
	public function init(){
		$this->arr = ['username'=>'zhangsan','info'=>['sex'=>'男','age'=>18]];
		$this->addObserve(self::BEFORE_EDIT,[$this,'beforeAction']);
		$this->addObserve(self::AFTER_LOGIN,"sendMsg");
		$this->addObserve(self::AFTER_LOGIN,"sendEmail");

		
	}
	public function beforeAction($obj){
		// var_dump($obj);die;
	}
	public function actionIndex(){
		// $obj = uu();

		if(F('users')){
			$data=F('users');
		}else{
		$model = M('StuModel');
		$data = $model->where(['id'=>array('>=',10),'title'=>array('<>','苏玲玲'),'_complex'=>'and'])->select();
		F('users',$data);
		}
		return $this->render('index',['data'=>$data]);
		
	}
	public function actionEdit(){
		$this->trigger(self::BEFORE_EDIT);
		$id=$_GET['id'];
		// echo $id;die;
		$model= new StuModel;
		$data=$model->where(['id'=>$id])->select();
		$data=$data[0];
		return $this->render('edit',['data'=>$data]);
	}
	public function actionUpdate(){
		$data=Request::$data;
		
		
		$model = new StuModel;
		$model->addObserve(StuModel::BEFORE_SAVE,[$model,"beforeSave"]);
		// echo 1;die;
		// var_dump($model);die;
		if($model->data($data)->save()){
			// echo 1;die;
			return $this->go('修改成功',U('index'));
		}
		// echo 1;die;
		return $this->go($model->getError());
	}
	public function actionInsert(){
		$data=$_POST;
		// var_dump($data);
		$model = new StuModel;
		// var_dump($model);die;
		
		// var_dump($model->data($data));
		if($model->data($data)->save()){
			return "成功";
		}else{
			return "失败";
		}
	}
	public  function actionLogin(){
		return $this->render('login');
	}
	public function actionDoLogin(){

		$this->trigger(self::AFTER_LOGIN);
		die;
		return $this->go('用户登录成功',U('index'));
	}
}