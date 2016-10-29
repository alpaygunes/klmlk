<?php
class user_manegement_mdl extends BaseModel{
	function __construct($parent){
		parent::__construct($parent);
	}
	
	function listele(){
		$sql 	= "SELECT * FROM ".DB_PRFX."_users ";
		$db		= $this->parent->db;
		$kullanicilar	= $db->get_results($sql);
		return  $kullanicilar;
	}
	
	function ekle(){
		$user						= new User();
		$user->adi_soyadi			= $this->request['adi_soyadi'];
		$user->username				= $this->request['username'];
		$user->password				= $this->request['password'];
		$user->rol					= $this->request['rol'];
		$user->adi_soyadi			= $this->request['adi_soyadi'];
		//$user->file					= $_FILES['resim'];
		if(isset($this->request['user_id'])){
			$user->user_id				= $this->request['user_id'];
		}
	
		if(!$user->save()){
			if (isset($_SESSION['error'])) {
				$url		= "?component=user_manegement&command=edit&user_id[]=".$this->request['user_id'];
				$this->portal->redirectUrl($url);
			}
			foreach ($user->error as $error){
				$this->portal->messages[] = array("type"=>"warning","text"=>$error);
			}
			return false;
		}else{
			foreach ($user->error as $error){
				$this->portal->messages[] = array("type"=>"info","text"=>$error);
			}
		}
		return true;
	}
	
	
	function sil(){
		$user_idler 		= $this->portal->request['user_id'];
		$user_idler_str		= implode(',', $user_idler);
		$sql 				="DELETE FROM ". DB_PRFX."_users WHERE user_id IN($user_idler_str)";
		$this->db->query($sql);
	}
}