<?php
$name	= 'frontpage';
include $name.'_mdl.php';
include $name.'_ctrl.php';
include 'views/'.$name.'_view.php';
class  frontpage extends BaseComponent{
	function  __construct(){
		parent::__construct($this);
	}
}
