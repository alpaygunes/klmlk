<?php
 class iBaseItem{
	var $db;
	var $BT_table_name;
	var $fileds	= array();
	var $values;
	var $id;
	function __construct($id=null,$BT_table_name=null){
		global $db;
		$this->id				= $id;
		$this->db				= $db;
		$this->BT_table_name	= $BT_table_name;
	}
	function getTableField(){
		$query	  		="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$this->BT_table_name'";
		$this->fileds  	=$this->db->get_results($query);
	}
	function save(){
		global $CORE;
		$isnew = false;
		if($this->id==null){
			$isnew=true;
		}
		$new_fields_values = array();
		$this->getTableField();
		$CORE->request['tarih'] = date('d.m.Y');
		//her bir alanın değerini formdan alalım
		foreach ($this->fileds as $key=>$value){
			$fld	= $value->COLUMN_NAME;
			if(isset($CORE->request[$fld])){

				$new_fields_values[$fld] =  addslashes($CORE->request[$fld]);
			}
		}
		//editorün idsinide ekleyelim
		$user	= new User();
		$user->getUserById($_SESSION['user_id']);
		$new_fields_values['editorID'] 	=  $user->user_id;

		$fld		='';
		$val 		='';

		//gelen menü simgesini kaydedelim
		if($_FILES["menu_simgesi"]["size"]>0){
			$temp 			= explode ( ".", $_FILES ["menu_simgesi"] ["name"] );
			$extension 		= end ( $temp );
			if($extension!="png"){
				$CORE->messages[] = array("type"=>"warning","text"=>"Sembol sadece png türünde olabilir");
				return false;
			}else{
				$simge_dizini		= configuration::$image_dir.'simgeler/';
				$simge_yolu			= configuration::$image_dir.'simgeler/' . time().".$extension";
				if (! file_exists ("../".$simge_dizini)){
					mkdir("../".$simge_dizini, 0777,true);
				}
				move_uploaded_file ( $_FILES ["menu_simgesi"] ["tmp_name"], "../".$simge_yolu );
				$new_fields_values['menu_simgesi'] 	=  $simge_yolu;
			}
		}

		// diğer dataları DB ye ekleyelim
		if($isnew){//----------------------------------------- new record
			foreach ($new_fields_values as $key=>$value){
				$fld	.= "$key,";
				$val	.= "'$value',";
			}
			$fld 		= rtrim($fld, ",");
			$val 		= rtrim($val, ",");
			$query 		= "INSERT INTO $this->BT_table_name".
						" ($fld) VALUES ($val)";
		}else{//----------------------------------------------update
			foreach ($new_fields_values as $key=>$value){
				if ($key!='id') {
					$fld.= " $key =  '$value' ,";
 				}
			}
			$fld 		= rtrim($fld, ",");
			$query 		= "UPDATE $this->BT_table_name SET " . $fld.
			 			  "WHERE id ='".$new_fields_values['id']."'";
		}
		$this->db->query($query);
		return true;
	}

	function delete(){
		global $CORE;
		$where 		= " id = '$this->id'";
		if(is_array($CORE->request['id'])){
			$idIN 	= implode(",", $CORE->request['id']);
			$where 	= " id IN ($idIN)";
		}
		$query 		= "DELETE FROM $this->BT_table_name WHERE $where";
		$this->db->query($query);
	}

	function getItem(){
		$query 			= "SELECT * FROM ".$this->BT_table_name ." WHERE id='".$this->id."'";
		$this->values 	= $this->db->get_results($query);
		return $this->values ;
	}

	function getAllItems($pagination=null){
		global $CORE;
		$where 									= ' WHERE ';
		$limit									= null;
		if(isset($CORE->request['ovner'])){
			$ovner							 = $CORE->request['ovner'];
			$where 							.= " ovner = '" .$ovner."' AND";
		}
		if($pagination){
			$request							= $CORE->request;
			if(isset($request['categoryID']) && $request['categoryID']>0){
				$where 							.= " categoryID = '" .$request['categoryID']."' AND";
			}
			$where 								.="  1";
			//kayıt sayısını alalım
			$query 								= "SELECT COUNT(*) FROM ".$this->BT_table_name . $where;
			$CORE->pagination->total_item_count =  $this->db->get_var($query);

			$CORE->pagination->initialize();
			$start								= $CORE->pagination->limit_start;
			$item_per_page						= $CORE->pagination->item_per_page;
			$limit 								.=" LIMIT $start,$item_per_page";
		}else{
			$where 								.="  1";
		}

		$query 								= "SELECT * FROM ".$this->BT_table_name . $where . $limit ;
		$this->values 						= $this->db->get_results($query);

		return $this->values ;
	}

}