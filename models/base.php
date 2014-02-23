<?php

class BaseModel {
	
	protected $db,$id,$fields = array();
	
	public function __construct($db,$id = null,$fields = array(),$suffix = ''){
		$this->db = $db;
		$this->id = $id;
		if(!empty($fields) && isset($this->id))$this->getFields($fields,$suffix);
	}	
	
	public function getId(){
		return $this->id;	
	}
	
	public static function getSuffix($region){
		switch($region){
			case '':exit('Region for suffix not set:'.print_r(debug_backtrace(),true));
					break;
			case 0:return '-eu';
			case 1:return '-na';
			case 2:return '-ru';
			case 3:return '-sea';
			case 4:return '-vn';
		}
	}
	
	protected function getChildren($fields = array(),$suffix = ''){
		if(isset($this->id)){
			if(static::childSuffix)$suffix = self::getSuffix($suffix);
			if($fields != '*')$fields = '`'.implode('`, `',$fields).'`';
			if($fields == '``')$fields = '`id`';elseif($fields != '*') $fields = '`id`, '.$fields;
			$query = 'SELECT '.$fields.' FROM `'.static::childTableName.$suffix.'` WHERE `'.static::foreignKey.'` = '.$this->id;
			$res = $this->db->query($query);
			$ret = array();
			foreach($res as $result){
				$className = static::childClassName;
				$newClass = new $className($this->db);
				foreach ($result as $field => $value){
					if($field != 'id')$newClass->setField($field, $value);
					else $newClass->setId($value);
				}
				$ret[] = $newClass;
			}
			
			return $ret;
		}else return false;
	}
	
	protected function getChild($fields = array(),$suffix = ''){
		if(isset($this->id)){
			$childId = $this->getField(static::foreignKey,$suffix);
			
			$className = static::childClassName;
			$ret = new $className($this->db,$childId,$fields,$suffix);
				
			return $ret;
		}else return false;
	}
	
	protected function getSuffixFromParent(){
		return '';
	}
	
	protected function getField($field,$suffix = ''){
		if(array_key_exists($field,$this->fields))return $this->fields[$field];
		else if(isset($this->id)){
			if(static::suffix)$suffix = $suffix == ''?self::getSuffix($this->getSuffixFromParent()):self::getSuffix($suffix);
			$query = 'SELECT `'.$field.'` FROM `'.static::tableName.$suffix.'` WHERE `id` = '.$this->id;
			$res = $this->db->query($query);
			if(isset($res[0][$field])){
				$this->fields[$field] = $res[0][$field];
				return $this->fields[$field];
			}else return false;
		}else return false;
	}
	
	protected function getFields($fields, $suffix = ''){
		if($fields != '*')$fields = '`'.implode('`, `',$fields).'`';
		if(static::suffix)$suffix = self::getSuffix($suffix);
		$query = 'SELECT '.$fields.' FROM `'.static::tableName.$suffix.'` WHERE `id` = '.$this->id;
		$res = $this->db->query($query);
		if(isset($res[0])){
			foreach($res[0] as $field => $value){
				$this->setField($field, $value);	
			}
		}else return false;
	}
	
	protected function setField($field,$value){
		$this->fields[$field] = $value;
	}
	
	protected function setFields($data){
		foreach ($data as $field => $value){
			$this->setField($field,$value);
		}
	}
	
	public static function escapeString($string){
		if(is_array($string)){
			$ret = array();
			foreach($string as $s)$ret[] = self::escapeString($s);
			return $ret;
		}
		if($string == null)return 'NULL';
		if(!is_numeric($string))return "'".addslashes($string)."'"; 
		return $string;
	}
	
	public static function findFirst($db,$conditions = array(),$order = array(),$fields = array(),$suffix = ''){
		$ret = self::find($db,$conditions,$order,array(0,1),$fields,$suffix);
		if(!empty($ret))return $ret[0];
		else return false;
	}
	
	public static function find($db,$conditions = array(),$order = array(),$limit = null,$fields = array(),$suffix = ''){
		$where = '1';
		foreach($conditions as $key => $condition){
			if(is_array($condition)){
				$condString = implode(', ',self::escapeString($condition));
				$where .= ' AND `'.$key.'` IN ('.$condString.')';
			}else
				$where .= ' AND `'.$key.'` = '.self::escapeString($condition);
		}
		$sort = !empty($order)?'ORDER BY':'';
		foreach($order as $key => $dir){
			$sort .= ' '.$key.' '.$dir;
		}
		$limit = isset($limit)?'LIMIT '.$limit[0].', '.$limit[1]:'';
		if(static::suffix)$suffix = self::getSuffix($suffix);
		if($fields != '*')$fields = '`'.implode('`, `',$fields).'`';
		if($fields == '``')$fields = '`id`';elseif($fields != '*') $fields = '`id`, '.$fields;
		$query = 'SELECT '.$fields.' FROM `'.static::tableName.$suffix.'` WHERE '.$where.' '.$sort.' '.$limit;
		$res = $db->query($query);
		$ret = array();
		foreach($res as $result){
			$className = static::className;
			$newClass = new $className($db);
			foreach ($result as $field => $value){
				if($field != 'id')$newClass->setField($field, $value);
				else $newClass->setId($value);
			}
			foreach($conditions as $field => $value)if(!is_array($value)){
				if($field != 'id')$newClass->setField($field, $value);
				else $newClass->setId($value);
			}
			$ret[] = $newClass;
		}
		return $ret;
	}
	
	public static function sql($db,$q){
		$res = $db->query($q);
		$ret = array();
		foreach($res as $result){
			$className = static::className;
			$ret[] = new $className($db,$result['id']);
		}
		return $ret;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function delete($suffix = ''){
		if(isset($this->id)) {
			if(static::suffix)$suffix = self::getSuffix($suffix);
			$query = "DELETE FROM ".static::tableName.$suffix." WHERE `id` = ".$this->id;
			$this->db->query($query);
		}
	}
	
	public function update($data = array(),$suffix = ''){
		if(isset($this->id)) {
			$this->setFields($data);
			$data = array();
			foreach($this->fields as $field => $value){
				$data[] = '`'.$field.'` = '.self::escapeString($value);
			}
			$dataString = implode(', ', $data);
			if(static::suffix)$suffix = self::getSuffix($suffix);
			$query = "UPDATE `".static::tableName.$suffix."` SET ".$dataString." WHERE `id` = ".$this->id;
			$this->db->query($query);
		}
	}
	
	public function insert($data = array(),$suffix = ''){
		$this->setFields($data);
		$data = $this->fields;
		$columns = implode(',',array_map(function($n){return '`'.$n.'`';},array_keys($data)));
		$values = implode(',',array_map(function($n){if(!is_numeric($n))return "'".$n."'";else return $n;},array_values($data)));
		if(static::suffix)$suffix = self::getSuffix($suffix);
		$query = "INSERT INTO `".static::tableName.$suffix."` (".$columns.") VALUES (".$values.")";
		$this->db->query($query);
		$this->id = $this->db->mysqli->insert_id;
	}
	
}