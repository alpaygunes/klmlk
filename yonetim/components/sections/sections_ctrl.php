<?php
class sections_ctrl extends BaseController{
	var $section;
	function __construct($parent){
		parent::__construct($parent);
		if(!isset($this->parent->request['command'])){
			$this->hepsiniListele();
		}
	}
	function hepsiniListele(){
		$this->section = new iSection('',DB_PRFX.'_sections');
		$this->portal->ASSIGNED['values'] = $this->section->getAllItems($pagination=1);
	}
	function _new(){
		$this->parent->view->layout	= 'form.php';
	}
	function save(){
		$this->section 						= new iSection($this->id,DB_PRFX.'_sections');
		$islem 								= $this->section->save();
		$this->hepsiniListele();
	}
	function edit(){
		$this->section 						= new iSection($this->id,DB_PRFX.'_sections');
		$data 								= $this->section->getItem();
		$this->portal->ASSIGNED['data'] 	= $data;
		$this->parent->view->layout			= 'form.php';
	}
	function delete(){
		global $CORE;
		$categories							= $this->parent->model->getSectionCategories();
		if(!count($categories)){
			$this->section 				= new iCategory($this->id,DB_PRFX.'_sections');
			$this->section->delete();
		}else{
			$this->portal->messages[] 		= array("type"=>"warning","text"=>"Silmek istediğiniz bölümlere ait kategoriler var.");
		}
		$this->hepsiniListele();		
	}
}