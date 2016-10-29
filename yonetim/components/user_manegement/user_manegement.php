<?php
if (!defined('GIRIS_KARTI')) {echo "Anormal giriş";exit();}
$name	= 'user_manegement';
include $name.'_mdl.php';
include $name.'_ctrl.php';
include 'views/'.$name.'_view.php';

class  user_manegement extends BaseComponent{
	function  __construct(){
		parent::__construct($this);
	}
}
