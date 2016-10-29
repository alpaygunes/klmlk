<?php
class media_sections_mdl extends BaseModel{
	function __construct($parent){
		parent::__construct($parent);
	}
	
	function getSectionCategories(){
		global $CORE;
		$where 								= " WHERE sectionID IN (" .implode(',', $this->request['id']).")";
		$query 								= " SELECT * FROM ". DB_PRFX.'_media_categories ' . $where  ;
		return  $this->db->get_results($query);
	}
}