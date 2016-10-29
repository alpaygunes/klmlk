<?php
class mole_ctrl extends BaseController{
	function __construct($parent){
		parent::__construct($parent);
	}

	function gorevListele(){
		$this->parent->model->aktiflikZamaniniGuncelle();
		$this->parent->model->gorevListele();
	}
}