<?php
class login_ctrl extends Base{
	var $model_asigneds = array();
	function __construct(){
		parent::__construct($this);
		if(isset($this->request['logout'])){
			$this->logout();
		}

        if($_SESSION['rol']=='uye'){
            $this->logout();
        }
		
		//eğer kullanıcı oturum açmamışsa
		if(!isset($_SESSION['user_id'])){
			if(isset($this->request['login']) && $this->request['login']=='login'){
				$user							= new User();
				$user->getUser($this->request['username'],$this->request['password']);
				if($user->rol=="yonetici" || $user->rol=="editor"){
					$arymsg 					= array();
					$arymsg ['type'] 			= 'info';
					$arymsg ['text'] 			= 'Hoşgeldiniz. '.$this->request['username'];
					$this->portal->messages[] 	= $arymsg;
                    $_SESSION['username']	= $user->username;
                    $_SESSION['rol']	    = $user->rol;
                    $_SESSION['adi_soyadi']	= $user->adi_soyadi;
                    $_SESSION['user_id']	= $user->user_id;
					$this->portal->redirectUrl('index.php');
				}else{
                    setcookie('username',null,time()-60);
                    setcookie('user_id',null,time()-60);
                    setcookie('adi_soyadi',null,time()-60);
                    unset($_SESSION['username']);
                    unset($_SESSION['user_id']);
                    $_SESSION["error"] 		= array("type"=>"warning",
                                                    "text"=>"Yetkisiz kullanıcı");
                    $this->portal->redirectUrl('index.php');
				}
			}else{
				$this->showForm();
			}
		}else{
			$this->showInfo();
		}
				
		
	}
	function showForm(){
		$CHTML 								= $GLOBALS['CHTML'];
		include 'modules/login/form.php';
	}
	function showInfo(){
		$CHTML 								= $GLOBALS['CHTML'];
		include 'modules/login/info.php';
	}

	function logout(){
		session_destroy();
		setcookie('username',null,time()-60);
		setcookie('user_id',null,time()-60);
        setcookie('adi_soyadi',null,time()-60);
		$this->portal->redirectUrl('http://www.okulcan.com');
	}
}