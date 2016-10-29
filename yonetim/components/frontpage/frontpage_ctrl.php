<?php
class frontpage_ctrl extends BaseController{
	
	function __construct($parent){
		parent::__construct($parent);
	}

	function ekle(){
		$this->parent->model->getir();
	}
}