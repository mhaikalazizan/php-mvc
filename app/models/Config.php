<?php

class Config
{
	public $url = "https://something.com";
	public $data = [];

	public function set($key, $data){
		$this->data[$key] = $data;
	}

	public function load(){
		return $this->data;
	}
}
