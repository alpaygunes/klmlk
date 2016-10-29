<?php
if (!defined('GIRIS_KARTI')) {echo "Anormal giriş";exit();}
$name	= 'sections';
include $name.'_mdl.php';
include $name.'_ctrl.php';
include 'views/'.$name.'_view.php';

class sections extends BaseComponent{
	function  __construct(){
		parent::__construct($this);
	}
}