<?php

class Clan extends BaseModel{
	
	const tableName = 'clans';
	const className = 'Clan';
	const suffix = false;
	
	public function setName($name){
		$this->setField('name', $name);
	}
	
	public function getName(){
		return $this->getField('name');
	}
	
	public function getTag(){
		return $this->getField('tag');
	}
	
	public function setTag($tag){
		$this->setField('tag', $tag);
	}
	
	public function getMotto(){
		return $this->getField('motto');
	}
	
	public function setMotto($motto){
		$this->setField('motto', $motto);
	}
	
	public function getDescription(){
		$desc = $this->getField('description');
		return preg_replace('%"(.*?)":(.*?)(\s|</p>)%', '<a href="$2">$1</a>$3', $desc);
	}
	
	public function setDescription($desc){
		$this->setField('description', $desc);
	}
	
	public function getStat($name){
		return $this->getField($name);
	}
	
	public function setStat($name,$value){
		$this->setField($name, $value);
	}
	
	public function getWid(){
		return $this->getField('wid');
	}
	
	public function setWid($wid){
		$this->setField('wid',$wid);
		$this->setField('region',$this->getRegion());
	}
	
	public function getUpdatedAt(){
		return $this->getField('updated_at');
	}
	
	public function exists(){
		return !($this->id == null);
	}
	
	public function isUpdated(){
		return !(time() - $this->getUpdatedAt() > UPDATE_INTERVAL);
	}
	
	public function getProvinces(){
		$r = $this->getRegion();
		$wr = new WotRequest($r);
		return  $wr->clanDataRequest('provinces/list',$this->getWid());
	}
	
	public function getBattles(){
		$r = $this->getRegion();
		$wr = new WotRequest($r);
		return  $wr->clanDataRequest('battles/list',$this->getWid());
	}
	
	public function saveToDB(){
		if($this->exists()){
			$this->update();
		}
		else $this->insert();
	}
	
	public function getRegion() {
		$wid = (float)$this->getWid();
		if($wid < 500000000)return 0;
		if($wid < 1000000000)return 1;
		if($wid < 2000000000)return 2;
		if($wid < 2500000000)return 3;
		if($wid < 3000000000)return 4;
		return 5;
	}
	
}