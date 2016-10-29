<?php
class dokuman_yonetimi_ctrl extends BaseController{
	var $section;
	function __construct($parent){
		parent::__construct($parent);
		if(!isset($this->parent->request['command'])){
			$this->hepsiniListele();
		}
	}
	function hepsiniListele(){
		$this->portal->request['ovner']				= "dokuman_yonetimi";
		$this->portal->ASSIGNED['values'] 			= $this->parent->model->getAllItems();
		$this->section 								= new iMediaSection('',DB_PRFX.'_media_sections');
		$this->portal->ASSIGNED['bolumler'] 		= $this->section->getAllItems();
		$this->portal->ASSIGNED['kategoriler']		= $this->parent->model->ajaxGetCategories();
	}
	
	function ajaxGetCategories(){
		echo $this->parent->model->ajaxGetCategories();
        exit();
	}
	function _new(){
		//$this->portal->request['ovner']				= "dokuman_yonetimi";
		//$this->section 								= new iMediaSection('',DB_PRFX.'_media_sections');
		//$this->portal->ASSIGNED['bolumler'] 		= $this->section->getAllItems();
		$this->parent->view->layout	= 'form.php';
	}
	
	function save(){
		$this->parent->model->save();
		if(count($this->parent->model->error)){
			foreach ($this->parent->model->error as $value) {
				$this->portal->messages[] = $value;
			}
			$this->request['id']		  = array($this->request['id']);
			$this->edit();
		}else{
			$this->hepsiniListele();
		}
	}
	function edit(){
		//$data										= $this->parent->model->getItem();
		//$this->portal->request['ovner']				= "dokuman_yonetimi";
		//$this->section 								= new iMediaSection('',DB_PRFX.'_media_sections');
		//$this->portal->ASSIGNED['bolumler'] 		= $this->section->getAllItems();
		//$this->portal->ASSIGNED['belgenin_bolumu']  = $this->parent->model->getBelgeninBolumu($data[0]->categoryID);
		//$this->portal->ASSIGNED['data'] 			= $data;
		$this->parent->view->layout					= 'form.php';
	}
	
	function delete(){
		$this->parent->model->delete();
		unset($this->parent->request);
		$this->hepsiniListele();
	}

    function dokumanKaydiniVer(){
        $this->parent->model->dokumanKaydiniVer();
    }

    function bolumleriVer(){
        $this->parent->model->bolumleriVer();
    }

    function kategorileriVer(){
        $this->parent->model->kategorileriVer();
    }

	function dosyayiKaydet(){
		$this->parent->model->dosyayiKaydet();
	}

	function kaydet(){
		$this->parent->model->kaydet();
	}

}