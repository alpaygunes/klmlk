<?php
class mole_panel_ctrl extends BaseController{
	function __construct($parent){
		parent::__construct($parent);
		$this->parent->BELLEK['veri']= $this->parent->model->getVeri();
	}

	function getAktifIstemciler(){
		header('Content-Type: application/json');
		$query = " SELECT
					  *
				   FROM
					  " . DB_PRFX . "_mole_istemciler";
		$istemciler = $this->db->get_results($query);
		echo json_encode($istemciler);
		exit();
	}
}