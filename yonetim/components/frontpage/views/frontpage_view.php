<?php
class frontpage_view extends BaseView{
	function __construct($parent=null){
		parent::__construct($parent);
	}
	function getoutput($layout=null){
		$CHTML = $GLOBALS['CHTML'];
		ob_start();
		include 'layout/'.$this->layout;
		$output				= ob_get_contents();
		ob_end_clean();
		return $output;
	}
}