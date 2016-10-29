<?php
class  BaseController extends Base{
	var $model;
	var $view;
	var $Cache;

	function __construct($parent){
		parent::__construct($parent);
		$this->Cache 	= $this->portal->loadHelper('Cache');
		$this->model	= $this->parent->model;
		$this->view		= $this->parent->view;

		if(isset($this->portal->request['id'])){
			if(is_array($this->portal->request['id'])){
				$this->id=$this->portal->request['id'][0];
			}else{
				$this->id=$this->portal->request['id'];
			}
		}else{
			$this->id	= null;
		}
		 $this->autoRun();
	}
	//gelen commandları direk çalıştır
	function autoRun(){
		if(array_key_exists('command',$this->request)){
			$command = $this->request['command'];
			if(method_exists($this,$command)){
				$this->$command();
			}
		}
	}
}