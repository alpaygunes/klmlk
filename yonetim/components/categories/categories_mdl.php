<?php
class categories_mdl extends BaseModel{
	function __construct($parent){
		parent::__construct($parent);
	}
	function ajaxGetCategories(){
		$categories 						= new iCategory('',DB_PRFX.'_categories');
		$categories							= $categories->iGetAllItems($this->request);
		$obj				= new stdClass();
		$obj->id  			= null;
		$obj->name 			= "Hepsi";
		array_unshift($categories,$obj);
		$output= "<select id=\"parentID\" name=\"parentID\">\n";
		foreach ($categories as $key => $value) {
			$selected='';
			if($this->request['parentID']==$value->id){
				$selected =" selected";
			}
			$output.="<option value=\"$value->id\" $selected >$value->name</option>\n";
		}
		$output.= "</select>";
		return $output;
	}
	function ajaxSectionGetCategories($sectionID){
		$section 			= new iSection($sectionID,DB_PRFX.'_sections');
		$categories			= $section->getCategories($sectionID);
		$category 			= new iCategory('',DB_PRFX.'_categories');
		
		$obj				= new stdClass();
		$obj->id  			= null;
		$obj->name 			= "Üst kategori olsun";
		if(!$categories){
			$categories		= array();
			$categories[]	= $obj;
		}else{
			array_unshift($categories,$obj);
		}
		$output= "<select id=\"parentID\" name=\"parentID\" class=\"form-control\">\n";
		foreach ($categories as $key => $value) {
			$selected='';
			if($this->request['parent_ID']==$value->id){
				$selected =" selected";
			}
			$output.="<option value=\"$value->id\" $selected>$value->name</option>\n";
		}
		$output.= "</select>";
		return $output;
	}

	function getCategoryArticles(){
		global $CORE;
		$where 								= " WHERE categoryID IN (" .implode(',', $this->request['id']).")";
		$query 								= " SELECT * FROM ". DB_PRFX.'_articles ' . $where  ;
		return  $this->db->get_results($query);
	}
	function getCategoryFirmalar(){
		global $CORE;
		$where 								= " WHERE categoryID IN (" .implode(',', $this->request['id']).")";
		$query 								= " SELECT * FROM ". DB_PRFX.'_firmalar ' . $where  ;
		return  $this->db->get_results($query);
	}
}



