<?php
class media_categories_ctrl extends BaseController{
	var $category;
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
		}else if($this->parent->request['ovner']=="dokuman_yonetimi"){
			$this->portal->ASSIGNED['ovner_label']="Döküman Yönetimi";
		}else if($this->parent->request['ovner']=="firmalar"){
			$this->portal->ASSIGNED['ovner_label']="Firma Yönetimi";
		}else if($this->parent->request['ovner']=="seriilanlar"){
			$this->portal->ASSIGNED['ovner_label']="Seri İlan Yönetimi";
		}
	}
	
	function hepsiniListele(){
		$this->getAllSections();
		$this->category 					= new iMediaCategory('',DB_PRFX.'_media_categories');
		$this->portal->ASSIGNED['values'] 	= $this->category->ajaxGetCategories($this->request,$pagination=true);
	}
	
	function getSectionName($sectionID) {
		return  $this->category->getSectionName($sectionID);
	}
	
	function getParentName($parentID) {
		return  $this->category->getParentName($parentID);
	}
	
	function _new(){
		$this->category 						= new iMediaCategory('',DB_PRFX.'_media_categories');
		$kategoriler							= $this->category->iGetAllItems();
		$this->portal->ASSIGNED['kategoriler'] 	= $kategoriler;
		$this->getAllSections();
		$this->parent->view->layout				= 'form.php';
	}
	
	function save(){
		$this->category 						= new iMediaCategory($this->id,DB_PRFX.'_media_categories');
		$islem 									= $this->category->save();
		if ($islem){
			$this->hepsiniListele();
		}else{
			$this->edit();
		}
	}
	
	function edit(){
		$this->category 						= new iMediaCategory($this->id,DB_PRFX.'_media_categories');
		$kategoriler							= $this->category->iGetAllItems();
		$this->portal->ASSIGNED['kategoriler'] 	= $kategoriler;
		$this->getAllSections();
		$data 									= $this->category->getItem();
		$this->portal->ASSIGNED['data'] 		= $data;
		$this->parent->view->layout				= 'form.php';
	}
	
	function delete(){
		$items 								    = $this->parent->model->getCategoryItems();
		if(!count($items)){
			$this->category 					= new iMediaCategory($this->id,DB_PRFX.'_media_categories');
			$this->category->delete();
		}else{
			$this->portal->messages[]           = array("type"=>"warning","text"=>"Silmek istediğiniz kategorilere ait öğeler var.");
		}
		$this->hepsiniListele();
	}
	
	function getAllSections(){
		$this->section 							= new iMediaSection('',DB_PRFX.'_media_sections');
		$this->portal->ASSIGNED['bolumler'] 	= $this->section->getAllItems();
	}
	
	function ajaxGetAllCategories() {
		echo  $this->parent->model->ajaxGetCategories();
        exit();
	}
	
	function ajaxSectionGetCategories() {
		$sectionID	= $this->request['sectionID'];
		echo  $this->parent->model->ajaxSectionGetCategories($sectionID);
        exit();
	}
}