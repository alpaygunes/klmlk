<?php
class iMediaSection extends iBaseItem{
	function __construct($id=null,$table_name){
		parent::__construct($id,$table_name);
	}
	function getCategories($sectionID){
		if(!$sectionID){
			return null;
		}
		$query 			= "SELECT * FROM ".DB_PRFX."_media_categories WHERE sectionID=".$sectionID;
		return  $this->db->get_results($query);
	}
}