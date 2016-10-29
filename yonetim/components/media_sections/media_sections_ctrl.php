<?php
class media_sections_ctrl extends BaseController{
	var $section;
	function __construct($parent){
		parent::__construct($parent);
		if(!isset($this->parent->request['command'])){
			$this->hepsiniListele();
		}
		if ($this->parent->request['ovner']=="fotogaleri") {
			$this->portal->ASSIGNED['ovner_label']="Foto Galeri";
		}else if($this->parent->request['ovner']=="belgeler") {
			$this->portal->ASSIGNED['ovner_label']="Belgeler";
		}else if($this->parent->request['ovner']=="mmvideo") {
			$this->portal->ASSIGNED['ovner_label']="Video Galeri";
		}else if($this->parent->request['ovner']=="belge_yonetimi") {
			$this->portal->ASSIGNED['ovner_label']="Belge Yönetimi";
		}else if($this->parent->request['ovner']=="firmalar"){
			$this->portal->ASSIGNED['ovner_label']="Firma Yönetimi";
		}else if($this->parent->request['ovner']=="seriilanlar"){
			$this->portal->ASSIGNED['ovner_label']="Seri İlan Yönetimi";
		}
	}
	function hepsiniListele(){
		$this->section = new iMediaSection('',DB_PRFX.'_media_sections');
		$this->portal->ASSIGNED['values'] = $this->section->getAllItems($pagination=1);
	}
	function _new(){
		$this->parent->view->layout	= 'form.php';
	}
	function save(){
		$this->section 						= new iMediaSection($this->id,DB_PRFX.'_media_sections');
		$islem 								= $this->section->save();
		$this->hepsiniListele();
	}
	function edit(){
		$this->section 						= new iMediaSection($this->id,DB_PRFX.'_media_sections');
		$data 								= $this->section->getItem();
		$this->portal->ASSIGNED['data'] 	= $data;
		$this->parent->view->layout			= 'form.php';
	}
	function delete(){
		global $CORE;
		$categories							= $this->parent->model->getSectionCategories();
		if(!count($categories)){
			$this->section 					= new iMediaCategory($this->id,DB_PRFX.'_media_sections');
			$this->section->delete();
		}else{
			$this->portal->messages[] 		= array("type"=>"warning","text"=>"Silmek istediğiniz bölümlere ait kategoriler var.");
		}
		$this->hepsiniListele();		
	}
}