<?php
if (!defined('GIRIS_KARTI')) {echo "Anormal giriş";exit();}
$name	= 'dokuman_yonetimi';
include $name.'_mdl.php';
include $name.'_ctrl.php';
include 'views/'.$name.'_view.php';

class dokuman_yonetimi extends BaseComponent{
	function  __construct(){
		parent::__construct($this);
	}
}