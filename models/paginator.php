<?php

class Paginator{
	
	private $count, $max, $offset, $startOffset, $endOffset;
	
	public function __construct($max,$offset){
		$this->max = $max;
		$this->offset = $offset;
	}
	
	public function getLimit(){
		return " LIMIT ".($this->offset*$this->max).",".$this->max;
	}
	
	public function getRealOffset(){
		return $this->max*$this->offset;
	}
	
	public function getOffset(){
		return $this->offset;
	}
	
	public function setCount($count){
		$this->count = $count;
		
		$maxOffset = ceil($this->count/$this->max);
		if($maxOffset > 14){
			$this->startOffset = $this->offset-7>=0?$this->offset-7:0;
			$over = $this->startOffset-($this->offset-7)+1;
			$this->endOffset = $this->offset+7+$over<$maxOffset?$this->offset+7+$over:$maxOffset;
			$over = $this->offset+7+$over-$this->endOffset;
			$this->startOffset = $this->startOffset-$over>=0?$this->startOffset-$over:0;
		}else{
			$this->startOffset = 0;
			$this->endOffset = $maxOffset;
		}
	}
	
	public function isPrev(){
		return $this->offset==0;
	}
	
	public function isNext(){
		$maxOffset = ceil($this->count/$this->max);
		return $this->offset>=$maxOffset-1;
	}
	
	public function prev(){
		return $this->offset;
	}
	
	public function next(){
		return $this->offset+2;
	}
	
	public function paginateList(){
		$ret = array();
		for($i=$this->startOffset;$i<$this->endOffset;$i++){$ret[] = $i+1;}
		return $ret;
	}
	
	public function current($o){
		return $o == $this->offset+1;
	}
	
}