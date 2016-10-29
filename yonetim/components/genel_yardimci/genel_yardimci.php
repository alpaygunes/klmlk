<?php
$name	    = 'genel_yardimci';
include $name.'_mdl.php';
include $name.'_ctrl.php';
include 'views/'.$name.'_view.php';
class  genel_yardimci extends BaseComponent{
	function  __construct(){
		parent::__construct($this);
	}
}
