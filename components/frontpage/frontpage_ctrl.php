<?php
class frontpage_ctrl extends BaseController{
	function __construct($parent){
		parent::__construct($parent);
	}

    function ajaxGetKelimeOnerileri(){
        $this->model->ajaxGetKelimeOnerileri();
    }

    function cikis(){
        session_destroy();
        $this->portal->redirectUrl("/");
        exit();
    }
}


