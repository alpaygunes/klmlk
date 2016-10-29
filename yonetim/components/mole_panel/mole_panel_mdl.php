<?php
class mole_panel_mdl extends BaseModel{
	function __construct($parent){
		parent::__construct($parent);
	}
	function getVeri(){
		return  "mole_panel model";
	}
}