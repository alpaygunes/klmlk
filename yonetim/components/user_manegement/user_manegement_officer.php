<?php
defined('GIRIS_KARTI') or die('Restricted access');

class user_manegement_officer extends Officer{
	var $DB_fields = array("user_id"=>null,"username"=>null,"password"=>null,"rol"=>"uye");
	function __construct($parent){
		parent::__construct('user_id', DB_PRFX."_users",$parent);
	}
}