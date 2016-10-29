<?php
class  BaseComponent extends Base{
	var $BELLEK 			= array();
	var $controller;
	var $model;
	var $view;
	var $toolBar;
	
	function  __construct($parent){		
		parent::__construct($parent);
		if(isset($this->request['sectionID']) && $this->request['sectionID']>0){
			$this->id		= $this->request['sectionID'];
		}
		$ctrl 						= $this->portal->component.'_ctrl';
		$mdl 						= $this->portal->component.'_mdl';
		$view 						= $this->portal->component.'_view';
		$this->portal->pagination	= new Pagination();
		$this->view 				= new $view($this);
		$this->model 				= new $mdl($this);
		$this->controller 			= new $ctrl($this);
		$this->toolBar				= new BaseToolBar($this);
	}
	
	function loadDBOfficer($officer){
		$officerFile	= 'components/'.$this->portal->component.'/'.$officer.'.php';
		if(file_exists($officerFile)){
			include ($officerFile);
		}	
	}
}