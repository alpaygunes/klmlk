<?php
class BaseAlbum{
	var $id;
	var $icon;
	var $name;
	var $basedir;
	var $dir = "albums/";
	var $BT_table_name;

	function __construct(){
		global $CORE,$configuration;
		if(isset($CORE->request['id'])){
			if(is_array($CORE->request['id'])){
				$this->id=$CORE->request['id'][0];
			}else{
				$this->id=$CORE->request['id'];
			}
		}else{
			$this->id=null;
		}
		$this->basedir			= configuration::$base_image_dir;
		$this->dir				= $this->basedir.DS.$this->id.DS;
		$this->BT_table_name	= DB_PRFX."_albums";
	}
	
	function getAllItems(){
		global $db;
		$query 			= "SELECT * FROM ".$this->BT_table_name;
		$this->values 	= $db->get_results($query);
		return $this->values ;
	}
	
	function save(){
		global $CORE,$db;
		$isnew = false;
		if($this->id==null){
			$isnew=true;
		}
		$name 	= $CORE->request['name'];
		if($isnew){//----------------------------------------- new record
			$query 	= "INSERT INTO ".DB_PRFX."_albums 
					   VALUES ('','$name','')";
			if($db->query($query)){
				echo $db->insert_id;
			}
		}else{
			$name 	= 	$CORE->request['name'];
			$query 	= 	"UPDATE ".DB_PRFX."_albums 
						SET name = '$name' 
						WHERE id ='".$this->id."'";
			if($db->query($query)){
				echo "guncellendi";
			}
		}
	}
	
	
	function ajaxSaveImage(){
		global $CORE,$db;
		$allowedExts 	= array("gif", "jpeg", "jpg", "png");
		$temp 			= explode(".", $_FILES["file"]["name"]);
		$extension 		= end($temp);
		$album_dir 		= $this->dir;
		if(!file_exists($album_dir)){
			mkdir($album_dir, 0777, true);
		}
		
		if ((($_FILES["file"]["type"] 	== "image/gif")
			|| ($_FILES["file"]["type"] == "image/jpeg")
			|| ($_FILES["file"]["type"] == "image/jpg")
			|| ($_FILES["file"]["type"] == "image/pjpeg")
			|| ($_FILES["file"]["type"] == "image/x-png")
			|| ($_FILES["file"]["type"] == "image/png"))
			&& ($_FILES["file"]["size"] < 5000000000)
			&& in_array($extension, $allowedExts)) {
			  if ($_FILES["file"]["error"] > 0) {
			    	echo "error##".$_FILES["file"]["error"];
			  } else {
				  	if (file_exists("$album_dir" . $_FILES["file"]["name"])) {
				  		echo "error##Farklı bir dosya adı seçin. Bu dosya mevcut";
				  	} else {
				  		$a = move_uploaded_file($_FILES["file"]["tmp_name"],"$album_dir" . $_FILES["file"]["name"]);
				  		echo "$album_dir" . $_FILES["file"]["name"]."##".$_FILES["file"]["name"];
				  	}
			  }
		} else {
		  	echo "error##Dosya formatınız uygun değil. *gif *png *jpg formatında ve/n boyutu en fazla 500 KB olmalı";
		}
	}
	
	function ajaxDeleteImage(){
		global $CORE,$db;
		$album_dir = $this->dir;
		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$imgname 	= $CORE->request['imgname'];
		$id 		= $CORE->request['id'];
		$extension 	= explode('.', $imgname);
		if (in_array($extension[count($extension)-1], $allowedExts)) {
				$a 	= unlink("$album_dir" . $imgname);	
				echo "Dosyanız silindi";
		}
	}
	
	function ajaxGetAlbumImages(){
		$output='';
		$dizin = opendir($this->dir);
		if ($dizin) {
			while (false !== ($dosya = readdir($dizin))) {
				if($dosya!='..' &&  $dosya!='.'){
					$output.=$this->dir.$dosya.'%%'.$dosya.'##';
				}
			}
			
			closedir($dizin);
		}
		$output = rtrim($output,"##");
		return $output;
	}
	
	function delete(){
		global $CORE,$db;
		$where 		= " id = '$CORE->request['id']'";
		if(is_array($CORE->request['id'])){
			$idIN 	= implode(",", $CORE->request['id']);
			$where 	= " id IN ($idIN)";
		}
		$query 		= "DELETE FROM $this->BT_table_name WHERE $where";
		$db->query($query);
		
		if (is_dir($this->dir)) {
			$dizin = opendir($this->dir);
			if ($dizin) {
				$sayac =0 ;
				while (false !== ($dosya = readdir($dizin))) {
					if($dosya!='..' &&  $dosya!='.'){
						$a 	= unlink("$this->dir" . $dosya);	
						$sayac++;
					}
				}
				$CORE->messages[] = array("type"=>"info","text"=>"$sayac dosya silindi.");
				closedir($dizin);
				rmdir($this->dir);
			}else{
				$CORE->messages[] = array("type"=>"warning","text"=>"Silme işlemi başarısız.");
			}
		}
	}
	
	function getItem(){
		global $db;
		$query 			= "SELECT * FROM ".$this->BT_table_name ." WHERE id='".$this->id."'";
		return 			$db->get_results($query);
	}
}