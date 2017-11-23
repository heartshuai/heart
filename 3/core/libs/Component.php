<?php

class Component
{
	public $observeList=[];

	public function addObserver($observe){
		if(array_search($observe,$this->observeList) == false){

		}
	}
	public function delObserve($observe){
		$key=array_search($observe,$this->observeList);
		if($key!==false){
			unset($this->observeList[]);
		}

	}
	public function notify(){
		foreach ($this->observeList as $observe) {
			$observe->update($this);
		}
	}
}