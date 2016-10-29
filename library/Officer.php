<?php
class Officer{
	var $id;
	var $table_name;
	var $component;
	var $db;
	var $request;
	
	function __construct($id,$table_name,$component){
		$this->id			= $id;
		$this->table_name	= $table_name;
		$this->component	= $component;
		$this->db			= $component->db;
		$this->request		= &$component->request;
	}

	function insert(){
		$sql0 		="INSERT INTO ".$this->table_name; 
		$vls		=null;
		$vls0		=null;
		foreach ($this->DB_fields as $key => $data){
				$vls0.= $key.",";
			  if(array_key_exists($key, $this->request)){
			  	$vls.= "'".$this->request[$key]."',";
			  }else{
			  	$vls.= "'".$data."',";
			  }
		}
		$vls 	= rtrim($vls, ",");
		$vls0 	= rtrim($vls0, ",");
		$sql	= $sql0 .' ('.$vls0.') '. ' VALUES '. '('.$vls.')';
		return $this->db->query($sql);
	}
	
	function update(){

	}
	
	function delete(){
		
	}
	
	function getData(){
		
	}
}