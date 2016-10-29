<?php
class user_manegement_view extends BaseView{
	function __construct($parent=null){
		parent::__construct($parent);
	}
	function getoutput($layout=null){
		$CHTML = $GLOBALS['CHTML'];
		if($layout){
			$this->layout 	= $layout;
		}
		ob_start();
		$toolBar			= $this->parent->toolBar;
		$bottons 			=array();
		if($this->layout=="user_manegement_layout.php"){
			$bottons[]	= 	$toolBar->_new();
			$bottons[]	= 	$toolBar->_edit();
			$bottons[]	= 	$toolBar->_delete();
		}else if($this->layout=="new_user.php"){
		 	
		}

		$toolBar->draw($bottons);
		
		include 'layout/'.$this->layout;
		$output				= ob_get_contents();
		ob_end_clean();
		return $output;
	}
}