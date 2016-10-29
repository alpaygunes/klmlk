<?php
class frontpage_mdl extends BaseModel{
	function __construct($parent){
		parent::__construct($parent);
	}
	function getVeri(){
		return  "Frontpage model";
	}
}