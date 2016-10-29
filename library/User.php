<?php
class User  {
    var $user_id;
    var $username;
    var $password;
    var $rol;
    var $db;
    var $error	= array();
    var $file;
    var $resim_yolu;
    var $adi_soyadi;

    function __construct(){
        $this->db		= $GLOBALS['db'];
        $this->portal   = $GLOBALS['CORE'];
    }
    function getUserByUsername($username){
        $sql				= "SELECT
                                    *
                                FROM
                                    ".DB_PRFX."_users " . "
                                WHERE
                                    username ="."'$username'";
        $user				= $this->db->get_row($sql);
        if(!$user){
            return false;
        }
        $this->user_id 			= $user->user_id;
        $this->username 		= $user->username;
        $this->rol 				= $user->rol;
        $this->adi_soyadi		= $user->adi_soyadi ;
        $this->resim_yolu		= $user->resim_yolu ;
        return true;
    }

    function getUserByID($ID){
        $sql					= "SELECT * FROM ".DB_PRFX."_users " .
            " WHERE  user_id =".$ID;
        $user					= $this->db->get_row($sql);
        if(!count($user)){
            $this->error[]	= "Kullanıcı bulunamadı";
            return false;
        }
        $this->user_id 			= $user->user_id;
        $this->username 		= $user->username;
        $this->rol 				= $user->rol;
        $this->adi_soyadi		= $user->adi_soyadi ;
        $this->resim_yolu		= $user->resim_yolu ;
        return $user;
    }

    function getUser($username,$password){
        $password 				= md5($password);
        $sql					= " SELECT * FROM ".DB_PRFX."_users " .
            " WHERE  username ="."'$username'" .
            " AND    password ="."'$password'";
        $user					= $this->db->get_row($sql);
        if(!count($user)){return false;}
        $this->user_id 			= $user->user_id;
        $this->username 		= $user->username;
        $this->rol 				= $user->rol;
        $this->adi_soyadi		= $user->adi_soyadi ;
        $this->resim_yolu		= $user->resim_yolu ;
        //$_SESSION['username']	= $user->username;
        //$_SESSION['rol']	    = $user->user_id;
        //$_SESSION['adi_soyadi']	= $user->adi_soyadi;
        return true;
    }

    function save(){
        $isNew				= true;
        if($this->user_id){
            $isNew 			= false;
        }


        //eğer resim göndeirldiyse kurallara uygunmu
      /*  if($this->file["size"]>0){
            //resmi dizine alalım
            $resim_kayit_dizini	= '../'.configuration::$user_image_dir;
            $allowedExts 		= array("gif", "jpeg", "jpg", "png");
            $temp 				= explode(".", $this->file["name"]);
            $extension 			= end($temp);
            $dosya_adi	  		= time().".".$extension;
            if($this->file["size"] > 60000){
                $_SESSION['error']	= array(
                    "type"=>"warning",
                    "text"=>"Seçtiğiniz dosyanın boyutu 50 KB dan az olmalı"
                );
                return false;
            }

            if ((($this->file["type"] 		== "image/gif")
                    || ($this->file["type"] == "image/jpeg")
                    || ($this->file["type"] == "image/jpg")
                    || ($this->file["type"] == "image/pjpeg")
                    || ($this->file["type"] == "image/x-png")
                    || ($this->file["type"] == "image/png"))
                && in_array($extension, $allowedExts)) {
                if ($this->file["error"] > 0) {
                    $this->error[]	= "Hata : ".$this->file["error"];
                    return false;
                } else {
                    if (file_exists($resim_kayit_dizini.$dosya_adi)) {
                        $this->error[]	= "Aynı isimde başka resim var. Tekrar deneyin.";
                        return false;
                    } else {
// 								move_uploaded_file($this->file["tmp_name"],
// 								$resim_kayit_dizini.$dosya_adi);
// 								$this->resim_yolu=configuration::$user_image_dir.$dosya_adi;
                    }
                }
            } else {
                $this->error[]	= "Uygunsuz dosya türü";
                return false;
            }
        }*/


        //yeni kayıt ise
        if($isNew){
            //kullanıcı adı  kayıtlımı66
            if(!$this->getUserByUsername($this->username)){
                //move_uploaded_file($this->file["tmp_name"],$resim_kayit_dizini.$dosya_adi);
                //$this->resim_yolu=configuration::$user_image_dir.$dosya_adi;

                $md5psw		= md5($this->password);
                $sql 		='INSERT INTO '.DB_PRFX."_users
				VALUE ('','$this->username','$md5psw','$this->rol','$this->adi_soyadi')";
                $this->db->query($sql);
                $this->error[]	= "Kayıt işlemi tamamlandır";
                return true;
            }else{
                $this->error[]	= "$this->username Kullanıcı adı başkasına iat";
                return false;
            }
            //güncelleme ise
        }else{
//            if($dosya_adi){
//                move_uploaded_file($this->file["tmp_name"],$resim_kayit_dizini.$dosya_adi);
//                $this->resim_yolu=configuration::$user_image_dir.$dosya_adi;
//            }

//            if($this->resim_yolu){
//                $resimQuery	= "resim_yolu	= '$this->resim_yolu',";
//            }

            $md5psw		= md5($this->password);
            $sql  ="UPDATE ".DB_PRFX."_users SET
						 	password	= '$md5psw' ,
					 		rol			= '$this->rol',
							adi_soyadi	= '$this->adi_soyadi'
							WHERE user_id='$this->user_id'";
            if($this->db->query($sql)){
                $this->error[] = "Güncelleme başarılı";
                return true;
            }else{
                $this->error[] = "Güncelleme başarısız" . " <p> " . $this->db->debug();
                return false;
            }
        }
    }
}
