<?php
class articles_ctrl extends BaseController{
	function __construct($parent){
		parent::__construct($parent);
		if(!isset($this->parent->request['command'])){
			$this->hepsiniListele();
		}
	}
	
	function _new(){
		$this->section 							= new iSection('',DB_PRFX.'_sections');
		$this->portal->ASSIGNED['bolumler'] 	= $this->section->getAllItems();
		$category 								= new iCategory('',DB_PRFX.'_categories');
		$this->portal->ASSIGNED['kategoriler']	= $category->getAllItems();
		$this->parent->view->layout				= 'form.php';
	}
	
	function ajaxSectionGetCategories() {
		$sectionID	= $this->request['sectionID'];
		echo  $this->parent->model->ajaxSectionGetCategories($sectionID);
        exit();
	}
	
	function save(){
		$article 						= new iArticle($this->id,DB_PRFX.'_articles');
		$islem 							= $article->save();
		$this->hepsiniListele();
	}
	
	function delete(){
		$this->article 					= new iArticle($this->id,DB_PRFX.'_articles');
		$this->article->delete();
		$this->portal->messages[] = array("type"=>"info","text"=>"silme işlemi başarılı");
		$this->hepsiniListele();
	}
	
	function hepsiniListele(){
		$this->section 							= new iSection('',DB_PRFX.'_sections');
		$this->portal->ASSIGNED['bolumler'] 	= $this->section->getAllItems();
		$this->portal->ASSIGNED['kategoriler']	= $this->parent->model->ajaxSectionGetCategories($this->request['sectionID']);
		$articles 								= new iArticle('',DB_PRFX.'_articles');
		$this->portal->ASSIGNED['values'] 		= $articles->getAllItems($this->request);
	}
	
	function ajaxGetAllCategories() {
		echo  $this->parent->model->ajaxGetCategories();
        exit();
	}
	
	function edit(){
		//güncelleme sırasında kaıylı bölüm ve kategori seçili olmalı
		$this->section 							= new iSection('',DB_PRFX.'_sections');
		$this->portal->ASSIGNED['bolumler'] 	= $this->section->getAllItems();
		
		$category 								= new iCategory('',DB_PRFX.'_categories');
		$this->portal->ASSIGNED['kategoriler']	= $category->getAllItems();
		
		$article 								= new iArticle($this->id,DB_PRFX.'_articles');
		$this->portal->ASSIGNED['artdata']		= $article->getItem();
		$this->portal->ASSIGNED['articleSectionID']	= $article->getSectionID($this->portal->ASSIGNED['artdata'][0]->categoryID);
		$this->parent->view->layout				= 'form.php';
	}
	
	function ajaxFileManager(){
		global $CORE;
		if(isset($CORE->request['dir'])){
			$CORE->request['dir'] 	= base64_decode($CORE->request['dir']);
		}
		if(isset($CORE->request['current_path'])){
			$CORE->request['current_path'] 	= base64_decode($CORE->request['current_path']);
		}
		$file_browser				= new FileBrowser();
		$file_browser->home_dir		= '../images/';
		$file_browser->user_dir		= $file_browser->home_dir.$_SESSION['username'];
		$file_browser->createUserDir($file_browser->user_dir);
		$file_browser->getItemList();
		echo $file_browser->draw();
        exit();
		
		//dosya yönetim sisteminde sorun var localde ajax çalışmıyor sunucuda resim upşoad edilmiyor
// 		$file_browser							= new FileBrowser();
// 		echo $file_browser->draw();
	}

}