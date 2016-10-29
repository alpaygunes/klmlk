<?php
class BaseView extends Base{
	var $layout;
	function __construct($parent=null){
		parent::__construct($parent);
			$this->layout		= $this->portal->component.'_layout.php';
			if (isset($this->request['layout'])){
				$this->layout 	= $this->request['layout'].'.php';
			}
	}
}