<?php
class categories_ctrl extends BaseController{
	var $category;
	function __construct($parent){
		parent::__construct($parent);
		if(!isset($this->parent->request['command'])){
			$this->hepsiniListele();
		}
	}
	function hepsiniListele(){
		$this->getAllSections();
		$this->category 					= new iCategory('',DB_PRFX.'_categories');
		$this->portal->ASSIGNED['values'] 	= $this->category->ajaxGetCategories($this->request,$pagination=true);
	}
	
	function getSectionName($sectionID) {
		return  $this->category->getSectionName($sectionID);
	}
	function getParentName($parentID) {
		return  $this->category->getParentName($parentID);
	}
	
	function _new(){
		$this->category 						= new iCategory('',DB_PRFX.'_categories');
		$kategoriler							= $this->category->iGetAllItems();
		$this->portal->ASSIGNED['kategoriler'] 	= $kategoriler;
		$this->getAllSections();
		$this->parent->view->layout				= 'form.php';
	}
	
	function save(){
		$this->category 						= new iCategory($this->id,DB_PRFX.'_categories');
		$islem 									= $this->category->save();
		$this->hepsiniListele();
	}
	
	function edit(){
		$this->category 						= new iCategory($this->id,DB_PRFX.'_categories');
		$kategoriler							= $this->category->iGetAllItems();
		$this->portal->ASSIGNED['kategoriler'] 	= $kategoriler;
		$this->getAllSections();
		$data 									= $this->category->getItem();
		$this->portal->ASSIGNED['data'] 		= $data;
		$this->parent->view->layout				= 'form.php';
	}
	
	function delete(){
		$articles 								= $this->parent->model->getCategoryArticles();
		$firmalar 								= $this->parent->model->getCategoryFirmalar();
		if(!count($articles)){
			if(!count($firmalar)){
				$this->category 					= new iCategory($this->id,DB_PRFX.'_categories');
				$this->category->delete();
			}else{
				$this->portal->messages[] = array("type"=>"warning","text"=>"Silmek istediğiniz kategorilere ait Firmalar var.");
			}
		}else{
			$this->portal->messages[] = array("type"=>"warning","text"=>"Silmek istediğiniz kategorilere ait makaleler var.");
		}
		$this->hepsiniListele();
	}
	
	function getAllSections(){
		$this->section 							= new iSection('',DB_PRFX.'_sections');
		$this->portal->ASSIGNED['bolumler'] 	= $this->section->getAllItems();
	}
	
	function ajaxGetAllCategories() {
		echo  $this->parent->model->ajaxGetCategories();
        exit;
	}
	function ajaxSectionGetCategories() {
		$sectionID	= $this->request['sectionID'];
		echo  $this->parent->model->ajaxSectionGetCategories($sectionID);
        exit;
	}
}