<?php
class iCategory extends iBaseItem{
	function __construct($id,$table_name){
		parent::__construct($id,$table_name);
	}
	function getSectionName($sectionID){
		$query = "SELECT name FROM ".DB_PRFX."_sections WHERE id='$sectionID'";
		$nm	=	$this->db->get_row($query);
		return $nm->name;
	}
	function getParentName($parentID){
		$query = "SELECT name FROM $this->BT_table_name WHERE id='$parentID'";
		$nm	=	$this->db->get_row($query);
		return $nm->name;
	}
	
	//sectioID gelmi yor sayfa iki defa post oluyor.
	function iGetAllItems($request=null){
		$where 		=' WHERE ';
		if(isset($request['sectionID']) && $request['sectionID']>0){
			$where 		.= " sectionID = '".$request['sectionID']."' AND";
		}
		if(isset($request['parentID']) && $request['parentID']>0){
			$where 		.= " parentID = '" .$request['parentID']."' AND";
		}
		$where 			.="  1";
		$query 			= "SELECT * FROM ".$this->BT_table_name . $where;
		$this->values 	= $this->db->get_results($query);
		return $this->values ;
	}
	
	
	//sectioID gelmi yor sayfa iki defa post oluyor.
	function ajaxGetCategories($request,$pagination=null){
		global $CORE;
		$where 								= ' WHERE ';
		$limit								= null;
		if($pagination){
			$request	=$CORE->request;
			if(isset($request['sectionID']) && $request['sectionID']>0){
				$where 							.= " sectionID = '" .$request['sectionID']."' AND";
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
		$query 									= "SELECT * FROM ".$this->BT_table_name . $where . $limit ;
		$this->values 							= $this->db->get_results($query);
		return $this->values ;
	}
}