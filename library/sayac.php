<?php
class sayac{
	var $db;
	var $core;
	var	$zaman_asimi;// X dk boyunca sayac yeniden artmasin. X sonra yeniden sayaci bir artir 
	var $cerez_adi;// istenen itemin ID sine göre bir id
	var $uri;
	function __construct($db,$CORE){
		$this->db			= $db;
		$this->core			= $CORE;
		$this->zaman_asimi 	= 5*60 ; // 5 dk
		$this->cerez_adi	= md5($_SERVER['QUERY_STRING']); // gelen adresi direk mdb 5 al kullan
		$this->uri			= $_SERVER['QUERY_STRING'];
		$this->uriSay();
		$this->genelSay();
	}
	
	
	function uriSay(){
		if(!isset($_COOKIE[$this->cerez_adi])){
			//eğer çerez yoksa önce çerezi bırak
			setcookie($this->cerez_adi,"1",time()+$this->zaman_asimi);
			//sonra say
			//eğer kayıt eklenmişse tekrar ekleme sadece güncelle
			$sql 		='SELECT COUNT(*) FROM '.DB_PRFX."_sayac WHERE md5QueryString = '$this->cerez_adi'";
			$result		= $this->db->get_var($sql);
			if($result){
				// kayıt var ise güncelleme yap
				$sql 		='UPDATE '.DB_PRFX."_sayac 
				SET ziyaret_sayisi=ziyaret_sayisi+1 
				WHERE md5QueryString = '$this->cerez_adi'";
			}else{
				//kayıt yok ise yeni ekleme yap
				$sql 		='INSERT INTO '.DB_PRFX."_sayac 
				(sayac_id,md5QueryString,uri,ziyaret_sayisi) 
				VALUE ('','$this->cerez_adi','$this->uri','1')";
			}
			$this->db->query($sql);
			//$this->db->vardump();
		}
	}
	
	function genelSay(){
		if(!isset($_COOKIE["anasayfa"])){
			setcookie("anasayfa","1",time()+$this->zaman_asimi);
			$sql 	='INSERT INTO '.DB_PRFX."_sayac 
					  (sayac_id,md5QueryString,uri,ziyaret_sayisi) 
					  VALUE ('','anasayfa','anasayfa','1')";
			$this->db->query($sql);
		}
	}
	
	function getUriCount(){
		$sql 		='SELECT ziyaret_sayisi FROM '.DB_PRFX."_sayac WHERE md5QueryString = '$this->cerez_adi'";
		$result		= $this->db->get_row($sql);
		return $result->ziyaret_sayisi;
	}
	
	function getSonXsaatZiyaretciSayisi($saat){
		$gecmislimiti	= time()-$saat*60*60;
		$sql 			= "SELECT COUNT(*) FROM ".DB_PRFX."_sayac   
							WHERE 
							  UNIX_TIMESTAMP(time) > '$gecmislimiti'
							  AND  uri = 'anasayfa'";
		$result			= $this->db->get_var($sql);
		return $result;
	}
	
	function getToplamZiyaretciSayisi(){
		$sql 		='SELECT COUNT(*) FROM '.DB_PRFX."_sayac WHERE uri = 'anasayfa'";
		$result		= $this->db->get_var($sql);
		return 		  $result;
	}
}