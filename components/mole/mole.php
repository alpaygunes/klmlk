<?php
$name	= 'mole';
include $name.'_mdl.php';
include $name.'_ctrl.php';
include 'views/'.$name.'_view.php';
class  mole extends BaseComponent{
	function  __construct(){
		parent::__construct($this);
	}
}
