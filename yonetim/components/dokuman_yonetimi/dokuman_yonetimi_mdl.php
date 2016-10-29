<?php
class dokuman_yonetimi_mdl extends BaseModel{
	var $BT_table_name;
	var $fields;
	var $error = array();
	var $dosyanin_kaydedildiyi_yol;
	function __construct($parent){
		parent::__construct($parent);
		$this->BT_table_name	= DB_PRFX."_dokuman_yonetimi";
	}
	
	function getAllItems(){
		$where 								= ' WHERE ';
		$limit								= null;
		if(isset($this->request['categoryID']) && $this->request['categoryID']>0){
			$where 							.= " categoryID = '" .$this->request['categoryID']."' AND";
		}
		$where 								.="  sahip_id=".$this->portal->user_id . " AND ";
		$where 								.="  1";

		//kayıt sayısını alalım
		$query 										= "SELECT COUNT(*) FROM ".$this->BT_table_name . $where;
		$this->portal->pagination->total_item_count =  $this->db->get_var($query);
		$this->portal->pagination->initialize();
		$start								= $this->portal->pagination->limit_start;
		$item_per_page						= $this->portal->pagination->item_per_page;
		$limit 								.=" LIMIT $start,$item_per_page";

		$query 								= "SELECT * FROM ".$this->BT_table_name . $where . $limit ;
		$this->values 						= $this->db->get_results($query);
	
		return $this->values ;
	}
	
	function ajaxGetCategories() {
		$sectionID			= $this->portal->request['sectionID'];
		$section 			= new iMediaSection($sectionID,DB_PRFX.'_media_sections');
		$categories			= $section->getCategories($sectionID);
		//$category 			= new iCategory(null,DB_PRFX.'_categories');
		if(count($categories)){
			$obj				= new stdClass();
			$obj->id  			= null;
			$obj->name 			= "Kategori Seçin";
			array_unshift($categories,$obj);
			$output= "<select id=\"categoryID\" class='form-control' name=\"categoryID\">\n";
			foreach ($categories as $key => $value) {
				$selected='';
				if($this->request['categoryID']==$value->id){
					$selected =" selected";
				}
				$output.="<option value=\"$value->id\" $selected>$value->name</option>\n";
			}
		}
		$output.= "</select>";
		return  $output;
	}
	
	function getTableField(){
		$query	  		="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$this->BT_table_name'";
		$this->fields  	=$this->db->get_results($query);
	}

	function kaydet()
	{
		global $CORE;
		$isnew 								= false;
		$new_fields_values 					= array();
		$dokuman_bilgileri                  = json_decode($this->request['dokuman_bilgileri'],true);
		$icerik_brans_ilisikligi            = json_decode($this->request['icerik_brans_ilisikligi'],true);
		$this->getTableField();
		foreach ($this->fields as $key=>$value)
		{
			$fld							= $value->COLUMN_NAME;
			if(isset($dokuman_bilgileri[$fld]))
			{
				$new_fields_values[$fld] 	=  $dokuman_bilgileri[$fld];
			}
		}

		if(!isset($dokuman_bilgileri['id']))
		{
			$isnew							= true;
		}

		$fld		='';
		$val 		='';
		if($isnew)
		{//------------------------------------------------------           YENİ KAYIT
			$target_file                        = configuration::$document_dir . time().'_'.$this->dosyaAdiYarat($_SESSION['orjinal_dosya_adi']);
			$new_fields_values['belgenin_yolu'] = $target_file;
			$new_fields_values['ekleme_tarihi'] = date('d.m.Y');
			$target_file                        = '../'.$target_file;
			$new_fields_values['sahip_id']      = $this->portal->user_id;
			foreach ($new_fields_values as $key=>$value){
				$fld	.= "$key,";
				$val	.= "'$value',";
			}
			$fld 		= rtrim($fld, ",");
			$val 		= rtrim($val, ",");
			$query 		= "INSERT INTO $this->BT_table_name".
				" ($fld) VALUES ($val)";
		}
		else
		{//-------------------------------------------------------------       GÜNCELLEME
			foreach ($new_fields_values as $key=>$value){
				if ($key!='id') {
					$value	 =  $value;
					$fld.= " $key =  '$value' ,";
				}
			}
			$fld 		= rtrim($fld, ",");
			$query 		= " UPDATE
									$this->BT_table_name
								SET "
									. $fld.
								" WHERE id ='"
									.$new_fields_values['id'].
								"' AND
									sahip_id='".$this->portal->user_id."'";
		}

		$sonuc = $this->db->query($query);
		if(!mysql_errno())
		{
			$kopyala = rename('../'.$_SESSION['gecici_dosya'],$target_file);
			if($isnew)
			{
				$sonucArr   = array('basarili'=>1,'id'=>$this->db->insert_id);
			}
			else
			{
				$sonucArr   = array('basarili'=>1,'id'=>$new_fields_values['id']);
			}
		}
		else
		{
			$sonucArr       = array('basarili'=>0,'id'=>$this->db->insert_id);
		}

		//eğer sorun yoksa branş ilişiklerini varsa önce sil sonra güncelle
		if($sonucArr['basarili'])
		{
			$icerik_id      = $sonucArr['id'];;
			//önce varolan ilişiği silelim
			$query          =" DELETE FROM
				                    ".DB_PRFX."_icerik_brans_iliskisi
							   WHERE
							        icerik_id= '".$icerik_id."'";
			$sil            = $this->db->query($query);

			foreach($icerik_brans_ilisikligi as $value){
				$brans_id   = $value['brans_id'];
				$query      = "INSERT INTO "
							  .DB_PRFX."_icerik_brans_iliskisi
							  VALUES('','$icerik_id','$brans_id')";
				$this->db->query($query);
			}
		}

		echo json_encode($sonucArr);
		exit();
	}

	function dosyaAdiYarat ($dosyaadi)
	{
		$text = trim($dosyaadi);
		$search = array('Ç','ç','Ğ','ğ','ı','İ','Ö','ö','Ş','ş','Ü','ü',' ');
		$replace = array('c','c','g','g','i','i','o','o','s','s','u','u','-');
		$new_text = str_replace($search,$replace,$text);
		return $new_text;
	}

	function delete(){
		//önce  dizindeki dosyaları silelim
		//db en silindiyse belgelerde dizinden silinsin
		foreach ($this->parent->request['id'] as $id) {
			$where 	= " WHERE
							sahip_id    = ".$this->portal->user_id. " AND id  =   ".$id;
			$query 	= " SELECT
									belgenin_yolu
							FROM
									".$this->BT_table_name.$where;
			$sonuc	= $this->db->get_var($query);
			unlink("../".$sonuc);
		}
		$where 		= "";
		if($this->parent->request['id'][0]>0){
			if(is_array($this->parent->request['id'])){
				$idIN 	= implode(",", $this->parent->request['id']);
				$where 	= " id IN ($idIN)";
			}
			$query 		= "DELETE FROM $this->BT_table_name WHERE $where";
			$this->db->query($query);

			//ilişikleride silelim
			if(is_array($this->parent->request['id'])){
				$idIN 	= implode(",", $this->parent->request['id']);
				$where 	= " icerik_id IN ($idIN)";
			}
			$query 		= "DELETE FROM "
										.DB_PRFX."_icerik_brans_iliskisi
									WHERE
										$where";
			$this->db->query($query);
		}
	}

    function dokumanKaydiniVer(){
        header('Content-Type: application/json');
        if($this->request['id']){
            $where          = " WHERE id=".$this->request['id'];
            $query          = "SELECT * FROM " .$this->BT_table_name.  $where;
            $dokuman_kaydi  = $this->db->get_results($query);
            $kategori_query = "SELECT
                                    sectionID as bolum_id
                                FROM
                                    ".DB_PRFX."_media_categories
                                WHERE
                                    id =".$dokuman_kaydi[0]->categoryID;
            $bolum_id       = $this->db->get_row($kategori_query);
            $dokuman_kaydi[0]->bolum_id = $bolum_id->bolum_id;
            echo json_encode($dokuman_kaydi);
        }else{
            echo json_encode(array());
        }
        exit();
    }

    function bolumleriVer(){
        header('Content-Type: application/json');
        $this->portal->request['ovner']			= "dokuman_yonetimi";
        $section 							= new iMediaSection('',DB_PRFX.'_media_sections');
        echo json_encode($section->getAllItems());
        exit();
    }

    function kategorileriVer(){
        header('Content-Type: application/json');
        $sectionID			= $this->portal->request['sectionID'];
		$section 			= new iMediaSection($sectionID,DB_PRFX.'_media_sections');
		$categories			= $section->getCategories($sectionID);
        echo json_encode($categories);
        exit();
    }

	function dosyayiKaydet()
	{
		header('Content-Type: application/json');
		$geri_bildirim      = array();
		$fileType           = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		$target_file_name   = configuration::$document_dir . '_tmp_'.md5($this->portal->username+time()) . '.' . $fileType;
		$boyut              = $_FILES['file']["size"];
		if ($boyut > 20000000) {
			$geri_bildirim['boyut_fazla'] = "Dosyanız çok büyük. 18 MB tan küçük olmalı.";
		}
		if ($_SERVER['CONTENT_LENGTH'] && !$_FILES && !$_POST) {
			$geri_bildirim['boyut_fazla'] = " Dosyanız çok büyük. " .ini_get("upload_max_filesize"). " tan küçük olmalı.";
			echo json_encode($geri_bildirim);
			exit();
		}

		if ($fileType != "zip" && $fileType != "rar") {
			$geri_bildirim['basarili'] = 1;
			$geri_bildirim['format_uygunsuz'] = "Dosyanızın formatı uygunsuz.
												Zip yada Rar formatında olmalı. Sıkıştırma programı kullanarak dönüştürebilirsiniz.";
		}

		if (!count($geri_bildirim)) {
			if($this->request['id']){// eğer id varsa güncellemdir sadece dosyayı aynı isimli dosyaile değiştimek yeter
				// gelen dosyayı eskisinin üzerine yaz
				$where          = " WHERE id=".$this->request['id'];
				$query          = "SELECT belgenin_yolu FROM " .$this->BT_table_name.  $where;
				$belgenin_yolu  = '../'.$this->db->get_var($query);
				if(file_exists($belgenin_yolu))
				{
					chmod($belgenin_yolu,0755);
					unlink($belgenin_yolu);
				}
				move_uploaded_file($_FILES["file"]["tmp_name"], $belgenin_yolu);
				$geri_bildirim['basarili'] = 1;
			}else
			{
				if (move_uploaded_file($_FILES["file"]["tmp_name"], '../'.$target_file_name)) {
					$geri_bildirim['basarili'] = 1;
					$_SESSION['gecici_dosya']    = $target_file_name;
					$_SESSION['orjinal_dosya_adi']       = $_FILES["file"]["name"];
				}
			}
			echo json_encode($geri_bildirim);
		} else {
			$geri_bildirim['basarili'] = 0;
			echo json_encode($geri_bildirim);
		}
		exit();
	}
}