<?php

class ColumnManager{
	
	private $columns = array(), $colNum;
	
	public function __construct($items,$colNum){
		$items;
		$this->colNum = $colNum;
		
		$this->columns = $this->calcColumns($items);
	}
	
	public function calcColumns($items,$colNum = null,$done = array()){
		if(empty($items))return $done;
		else{
			$colNum = isset($colNum)?$colNum:$this->colNum;
			$l = ceil(sizeof($items)/$colNum);
			$done[] = array_slice($items,0,$l);		
			$items = array_slice($items,$l,sizeof($items)-$l);
			return $this->calcColumns($items,$colNum-1,$done);
		}
	}
	
	public function getColumns(){
		return $this->columns;
	}	
	
}