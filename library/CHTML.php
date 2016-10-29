<?php
class CHTML{
	//<input type="radio" name="sex" value="male">
	static function input($type=null,$name=null,$value=null,$attribute=null){
		if(	$type=='text' || 
			$type=='checkbox' || 
			$type=='button'  || 
			$type=='password' || 
			$type=='hidden'){
			return "<input	type=\"$type\" 	name=\"$name\"	id=\"$name\" value=\"$value\" $attribute />"; 
		}else if($type=='textarea'){
			$a =  "<$type	  	name=\"$name\"	id=\"$name\"   $attribute > $value </$type>";
			return $a;
		}
	}
	
	static function select($name=null,$array,$selected_id=null,$attribute=null){
		$select.="<select id=\"$name\"  name=\"$name\" $attribute >";
		foreach ($array as $key=>$text){
			$sel='';
			if($text->id==$selected_id){
				$sel =" selected";
			}
			$select.="<option  $sel value=\"$text->id\">$text->name</option>";
		}
		$select.="</select>";
		return $select;
	}
}