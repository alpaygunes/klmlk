<?php

class login_ctrl extends Base{
	var $model_asigneds = array();
	function __construct(){
		parent::__construct($this);
		if(isset($this->request['logout'])){
			$this->logout();
		}

		if(isset($_COOKIE['username']) && !isset($_SESSION['username'])){
			$_SESSION['username']	= $_COOKIE['username'];
			$_SESSION['user_id']	= $_COOKIE['user_id'];
            $_SESSION['adi_soyadi']	= $_COOKIE['adi_soyadi'];
            $_SESSION['rol']	    = $_COOKIE['rol'];
			//ilk girişte adı soyadı falan yazmıyodu onedenle hatırlama işlemi olunca site yeniden yüklenmeli
			$this->portal->redirectUrl("index.php");
            //daha sonra kodnrol için bir betik yaz. bu bilgilerin hepsi tekkayıtta mevcutmu.
		}

        if($_SESSION['username']){
            $this->showInfo();
        }else{
            $this->showLoginLink();
        }
	}

	function showLoginLink(){
		include 'modules/login/login_Links.php';
	}

	function showInfo(){
		include 'modules/login/info.php';
	}

	function logout(){
		session_destroy();
		setcookie('username',null,time()-60);
		setcookie('user_id',null,time()-60);
        setcookie('rol',null,time()-60);
		$this->portal->redirectUrl('index.php');
	}

}