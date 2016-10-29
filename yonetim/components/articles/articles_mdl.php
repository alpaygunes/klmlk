<?php
class articles_mdl extends BaseModel{
	function __construct($parent){
		parent::__construct($parent);
	}
	function ajaxSectionGetCategories($sectionID){
		$section 			= new iSection($sectionID,DB_PRFX.'_sections');
		$categories			= $section->getCategories($sectionID);
		//$category 			= new iCategory(null,DB_PRFX.'_categories');
		if(count($categories)){
		$obj				= new stdClass();
		$obj->id  			= null;
		$obj->name 			= "Kategori Seçin";
		array_unshift($categories,$obj);
		$output= "<select id=\"categoryID\" name=\"categoryID\" class=\"form-control\">\n";
			foreach ($categories as $key => $value) {
				$selected='';
				if($this->request['categoryID']==$value->id){
					$selected =" selected";
				}
				$output.="<option value=\"$value->id\" $selected>$value->name</option>\n";
			}
		}
		$output.= "</select>";
		return $output;
	}

	function ajaxGetCategories(){
		$categories 						= new iCategory('',DB_PRFX.'_categories');
		$categories							= $categories->ajaxGetCategories($this->request);
		$obj				= new stdClass();
		$obj->id  			= null;
		$obj->name 			= "Hepsi";
		array_unshift($categories,$obj);
		$output= "<select id=\"categoryID\" name=\"categoryID\">\n";
		foreach ($categories as $key => $value) {
			$selected='';
			if(isset($this->request['categoryID'])){
				if($this->request['categoryID']==$value->id){
					$selected =" selected";
				}
			}
			$output.="<option value=\"$value->id\" $selected >$value->name</option>\n";
		}
		$output.= "</select>";
		return $output;
	}

}