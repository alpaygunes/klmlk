<?php
class menu_ctrl extends Base{
	var $model_asigneds 	= array();
	function __construct(){
		parent::__construct($this);
		$this->showForm();
	}
	function showForm(){
		$CHTML 				= $GLOBALS['CHTML'];
		include 'modules/menu/view.php';
	}
}