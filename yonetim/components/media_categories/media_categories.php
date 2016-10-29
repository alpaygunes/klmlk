<?php
if (!defined('GIRIS_KARTI')) {echo "Anormal giriş";exit();}
$name	= 'media_categories';
include $name.'_mdl.php';
include $name.'_ctrl.php';
include 'views/'.$name.'_view.php';

class media_categories extends BaseComponent{
	function  __construct(){
		parent::__construct($this);
	}
}