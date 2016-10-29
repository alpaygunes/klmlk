<?php
class user_manegement_ctrl extends BaseController{
	
	function __construct($parent){
		parent::__construct($parent);
		if(!isset($this->parent->request['command'])){
			$this->listele();
		}
	}
	
	function listele(){
		$kullanicilar 								= $this->parent->model->listele();
		$this->portal->ASSIGNED["kullanicilar"]		= $kullanicilar;
	}
	
	function _new(){
		$this->parent->view->layout					= 'new_user.php';
	}
	
	function edit(){
		$user										= new User();
		$user->getUserByID($this->parent->request['user_id'][0]);
		$this->portal->ASSIGNED['user']				= $user;
		$this->parent->view->layout					= 'new_user.php';
	}

	function insert(){
		if($this->parent->model->ekle()){
			$this->listele();
		}else{
			$this->parent->view->layout				= 'new_user.php';
		}
	}
	
	function delete(){
		$this->parent->model->sil();
		$this->listele();
	}
}