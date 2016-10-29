<?php
class BaseToolBar extends Base{
	var $parent;
	var $type;//vertical, horizonal
	function __construct($parent){
		parent::__construct($this);
		$this->parent = $parent;
	}
	function draw($bottons){
		echo '<nav class="navbar navbar-default">
              <div class="container-fluid">
              <ul class="nav navbar-nav">
              ';
			foreach ($bottons as $key=>$value){
				echo $value;
			}
		echo '
             </ul>
             </div><!-- /.container-fluid -->
             </nav>';
	}
 
	function _new(){
		return '<li class="TButton" id="new" command="_new" cancelSubmit="true">
                    <a href="#">'._YENI_.'</a>
				</li>
				';
	}
	function _save(){
		return '<li class="TButton" id="save" command="save">
                     <a href="#">'._KAYDET_.'</a>
				</li>
				';
	}
	function _update(){
		return '<li class="TButton" id="update" command="update" cancelSubmit="false">
                     <a href="#">'._GUNCELLE_.'</a>
				</li>
				';
	}
	
	function _edit(){
		return '<li class="TButton" id="edit" command="edit" cancelSubmit="false">
                     <a href="#">'._DUZENLE_.'</a>
				</li>
				';
	}
	
	function _delete(){
		return  '<li class="TButton" id="delete" command="delete" cancelSubmit="true">
                     <a href="#">'._SIL_.'</a>
				</li>
				';


	}
	function _insert(){
		return '<li class="TButton" id="insert" command="insert" cancelSubmit="false">
                     <a href="#">'._EKLE_.'</a>
				</li>
				';


	}
	function _close(){
		return '<li class="TButton" id="close" command="close" cancelSubmit="false">
                     <a href="#">'._KAPAT_.'</a>
				</li>
				';
	}
	function _back(){
		return '<li class="TButton" id="back" command="back" cancelSubmit="false">
                     <a href="#">'._GERI_.'</a>
				</li>
				';
	}
	function _custom($id,$name,$command,$cancelSubmit=NULL,$glyphicon=NULL){
		return '<li
					  class			="TButton"  
					  id			="'.$id.'" 
					  command		="'.$command.'"  
					   '.$cancelSubmit .' > '.
					   $glyphicon.'
				<a href="#">'.$name.'</a>
				</li>';
	}
}