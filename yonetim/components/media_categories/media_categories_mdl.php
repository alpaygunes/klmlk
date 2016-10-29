<?php
class media_categories_mdl extends BaseModel{
	function __construct($parent){
		parent::__construct($parent);
	}
	function ajaxGetCategories(){
		$categories 						= new iCategory('',DB_PRFX.'_media_categories');
		$categories							= $categories->iGetAllItems($this->request);
		$obj								= new stdClass();
		$obj->id  							= null;
		$obj->name 							= "Hepsi";
		array_unshift($categories,$obj);
		$output								= "<select id=\"parentID\" name=\"parentID\">\n";
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
		$section 			= new iMediaSection($sectionID,DB_PRFX.'_media_sections');
		$categories			= $section->getCategories($sectionID);
		$category 			= new iMediaCategory('',DB_PRFX.'_media_categories');
		
		$obj				= new stdClass();
		$obj->id  			= null;
		$obj->name 			= "Üst kategori olsun";
		if(!$categories){
			$categories		= array();
			$categories[]	= $obj;
		}else{
			array_unshift($categories,$obj);
		}
		$output= "<select id=\"parentID\" class='form-control' name=\"parentID\">\n";
		foreach ($categories as $key => $value) {
			$selected='';
			if($this->request['parent_ID']==$value->id){
				$selected ="selected";
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

	function getCategoryItems(){
		global $CORE;

		if($this->parent->request['ovner']=="dokuman_yonetimi"){
			$this->portal->ASSIGNED['ovner_db']=DB_PRFX."_dokuman_yonetimi";
		}

		$where 								= " WHERE categoryID IN (" .implode(',', $this->request['id']).")";
		$query 								= " SELECT * FROM ". $this->portal->ASSIGNED['ovner_db'] . $where  ;
		return  $this->db->get_results($query);
	}
}



