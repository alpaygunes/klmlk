<?php
class iArticle extends iBaseItem{
	function __construct($id=null,$table_name){
		parent::__construct($id,$table_name);
	}

	function getAllItems($request){
		global $CORE;
		$where 								=' WHERE ';
		$limit								=null;
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
		
		$query 								= "SELECT * FROM ".$this->BT_table_name . $where . $limit ;
		$this->values 						= $this->db->get_results($query);
		
		return $this->values ;
	}
	
	function getSectionID($categoriID){
		$query 								= "SELECT * FROM ".DB_PRFX."_categories WHERE id='$categoriID'" ;
		$result								= $this->db->get_results($query);
		$sectionID 							= $result[0]->sectionID;
		return $sectionID ;
	}
}