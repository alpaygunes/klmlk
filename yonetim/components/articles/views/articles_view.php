<?php
class articles_view extends BaseView{
	
	function __construct($parent=null){
		parent::__construct($parent);
	}
	function getoutput($layout=null){
		$CHTML = $GLOBALS['CHTML'];
		if($layout){
			$this->layout 	= 	$layout;
		}
		ob_start();
		$toolBar			= 	$this->parent->toolBar;
		$bottons 			=	array();
		if($this->layout=="articles_layout.php"){
			$bottons[]		= 	$toolBar->_delete();
			$bottons[]		= 	$toolBar->_new();
			$bottons[]		= 	$toolBar->_edit();
			$outputP 		=   $this->portal->pagination->draw();
		}else if($this->layout=="form.php"){
			$bottons[]		= 	$toolBar->_save();
		}
		$toolBar->draw($bottons);
		include 'layout/'.$this->layout;
		$output				= ob_get_contents().$outputP;
		ob_end_clean();
		return $output;
	}
}