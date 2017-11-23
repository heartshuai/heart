<?php
namespace libs;
use libs\Validate;
class Model extends \libs\Base
{
	const BEFORE_VALIDATE ='beforeValidate';
	const After_VALIDATE ='afterValidate';
	const BEFORE_SAVE='beforeSave';
	const AFTER_SAVE='aftersave';
	public $tableName;
	public $config;
	private $db;
	private $sql;
	private $where='';
	private $limit='';
	private $orderBy='';
	private $field='*';
	private $groupBy='';
	private $join='';
	private $fields;
	private $primaryKey;
	public $data=[];
	private $error=[];
	public function __construct(){
		$this->config=C('db');
		// var_dump($this->config);die;
		// $this->config=array_merge($this->config,$config);
		// var_dump($this->config);
		$type=ucfirst($this->config['type']);
		$objname='libs\\db\\'.$type;
		$this->db=BaseTree::get($objname,$this->config);
		$this->getFields();
		// var_dump($this->config);
	}
	//设置当前表名
	public function setTable($tableName){
		$this->tableName = $tableName;

	}
	public function table($table){
		$this->tableName=$table;
		return $this;
	}
	public function select(){
		//拼接sql
		$this->sql='select '.$this->field.' from '.$this->tableName;
		if($this->join){
			$this->sql.=' '.$this->join;
		}
		if($this->where){
			$this->sql.=' where '.$this->where;
		}

		if($this->orderBy){
			$this->sql.=' order by '.$this->orderBy;
		}
		if($this->limit){
			$this->sql.=' limit '.$this->limit;
		}
		if($this->groupBy){
			$this->sql.=' groupBy '.$this->groupBy;
		}

		// echo $this->sql;die;
		return $this->db->select($this->sql);
	}
	public function getSql(){
		return $this->sql;
	}
	public function limit(){
		$argmentsNum=func_num_args();
		if($argmentsNum==1){
			$this->limit=func_get_arg(0);
		}elseif($argmentsNum>1){
			$this->limit=func_get_arg(0).','.func_get_arg(1);
		}
		return $this;
		
	}
	/*
	where 条件 
	$mix mixed
	where('id=5')
	where(['id'=>5,'title'=>'test'])
	where(['id'=>5,'title'=>'test','_complex'=>'or']);
	where(['id'=>array('>',5)]);
	where(['id'=>array('in',array(1,2,3,4,5))]);
	where(['title'=>array('like','zhang%')]);
	 */
	public function where($mix){
		if(is_string($mix)){
			$this->where=$mix;
		}elseif(is_array($mix)){
			$where=[];
			$separator=' and ';
			if(isset($mix['_complex'])){
				$separator =" ".$mix['_complex']." ";
				unset($mix['_complex']);
			}
			foreach($mix as $key=>$v){
				if(is_array($v)){
					$v=implode("'",$v)."'";
					$where[]= '`'.$key.'`'.$v;
				}else{
					$where[]='`'.$key."`='".$v."'";
				}
				
			}
			$this->where=implode($separator,$where);
		}
		return $this;
	}
	public function orderBy($mix){
		if(is_string($mix)){
			$this->orderBy = $mix;
		}elseif(is_array($mix)){
			$order=[];
			foreach($mix as $key=>$v){
				if(is_integer($key)){
					$order[]=$v;
				}else{
					$order[] =$key.' '.$v;
				}
				
			}
			$this->orderBy=implode(',',$order);
		}
		return $this;
	}
	//$mix既可以传数据，而可以传字符串
	public function field($mix){
		if(is_array($mix)){
			$this->field=implode(',',$mix);
		}elseif(is_string($mix)){
			$this->field=$mix;
		}
		return $this;
	}
	public function groupBy($mix){
		if(is_string($mix)){
			$this->groupBy= $mix;
		}
		return $this;
	}
	public function join($mix){
		if(is_string($mix)){
			$this->join = $mix;
		}
		return $this;
	}
	public function data($data){
		$this->data = $data;
		return $this;
	}
	//更新操作
	public function save($data=''){

		if(is_array($data)){
			$this->data=array_merge($this->data,$data);
				}
		if(!$this->validate()){
			return false;
		}
		$this->trigger(self::BEFORE_SAVE,new EVENT(self::BEFORE_SAVE,$this));
				//过滤掉所有不是字段名称的这些数组元素
			$this->data= array_intersect_key($this->data, $this->fields);	
		// var_dump($this->primaryKey);
		// var_dump($this->data);
		// die;	
			//执行新增 修改操作
			if(array_key_exists($this->primaryKey,$this->data)){
				//修改
				$sql="replace into ".$this->tableName." set ";
				
				// echo $sql;die;
			}else{
				//新增
				$sql = "insert into ".$this->tableName." set ";
			}
				$dt=[];
				foreach($this->data as $key=>$v){
					$dt[] = "`".$key.'`="'.$v.'"';
				}

				$sql.=implode(',',$dt);
				// echo $sql;die;
				$res=$this->db->update($sql);
				$this->trigger(self::AFTER_SAVE);
			
			return $res;
			
			}
	public function getFields(){
		// echo 'desc '.$this->`tableName`;die;

	$fields = $this->db->select('desc `'.$this->tableName.'`');
	// var_dump($fields);die;
	foreach($fields as $row){
		$this->fields[$row['Field']] = $row;
		if($row['Key']=='PRI'){
			$this->primaryKey = $row['Field'];
		}
	}
	// var_dump($this->primaryKey);die;
	}
	public function getPrimaryKey(){
		//字段验证
		
	}
	public function validate(){
		$rules=$this->rules();
		if(is_array($rules)){
			//循环进行验证
			//每一项错误进行记录
			foreach($rules as $rule){
				//定义好$rule一行的数据格式
				//['username','required',params]
				if(is_array($rule)){
					//交给验证类使用
					$funcname=$rule[1];
					$field=$rule[0];
					$params = isset($rule['params'])?$rule['params']:[];
					if(isset(Validate::$validates[$funcname])){
						$method=Validate::$validates[$funcname];
						if(false===Validate::$method($this->data[$field],$params)){
							if(isset($rule['errormsg'])){
								$this->error[]=$rule['errormsg'];
							}else{
								$this->error[]=$field.Validate::$errorMsg[$funcname];
							}
						}
					}elseif(method_exists($this, $funcname)){
						if(false===$this->$funcname($this->data[$field],$params)){
							if(isset($rule['errormsg'])){
								$this->error[]=$rule['errormsg'];
							}else{
								$this->error[]=$field.'error';
							}
							
						}
					}
				}
			}
		}
		if(count($this->error)) return false;
		return true;
	}
	public function rules(){
		return [];
	}
	public function getError(){
		return $this->error[0];
	}
	public function getErrors(){
		return $this->error;
	}
}