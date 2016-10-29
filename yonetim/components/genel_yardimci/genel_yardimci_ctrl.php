<?php
class genel_yardimci_ctrl extends BaseController{
	function __construct($parent){
		parent::__construct($parent);
	}


    function branslariVer(){
        $this->parent->model->branslariVer();
    }


	function icerikBransIlisikleriniVer(){
		$this->parent->model->icerikBransIlisikleriniVer();
	}

















    function illeriVer(){
        $this->parent->model->illeriVer();
    }

    function ilceleriVer(){
        $this->parent->model->ilceleriVer();
    }


    function okulTurleriniVer(){
        $this->parent->model->okulTurleriniVer();
    }

    function profilResminiKAydet(){
        $this->parent->model->profilResminiKAydet();
    }


}