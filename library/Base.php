<?php
class Base {
	var $portal;
	var $parent;
	var $db;
	var $request;
	var $id;
	

	function __construct($parent){
		global $CORE,$db;
		$this->portal		= $CORE;
		$this->parent		= $parent;
		$this->db			= $db;
		$this->request		= &$CORE->request;
	}
}