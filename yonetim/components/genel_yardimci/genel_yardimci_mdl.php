<?php

class genel_yardimci_mdl extends BaseModel
{
	function __construct($parent)
	{
		parent::__construct($parent);
	}


	function gorevleriVer()
	{
		header('Content-Type: application/json');
		$query = " SELECT
								*
						FROM
								" . DB_PRFX . "_gorevler";
		$gorevler = $this->db->get_results($query);
		echo json_encode($gorevler);
		exit();
	}


	function branslariVer()
	{
		header('Content-Type: application/json');
		$query = " SELECT
								*
						FROM
								" . DB_PRFX . "_ogretmen_branslari";
		$branslar = $this->db->get_results($query);
		echo json_encode($branslar);
		exit();
	}


	function icerikBransIlisikleriniVer()
	{
		header('Content-Type: application/json');
		$icerik_id  = $this->request['icerik_id'];
		$query      = " SELECT
								brans_id
						FROM
								" . DB_PRFX . "_icerik_brans_iliskisi
						WHERE
								icerik_id=".$icerik_id;
		$ilisikli_branslar  = $this->db->get_results($query);
		echo json_encode($ilisikli_branslar);
		exit();
	}


	function illeriVer()
	{
		header('Content-Type: application/json');
		$query = " SELECT
								*
						FROM
								" . DB_PRFX . "_iller";
		$iller = $this->db->get_results($query);
		echo json_encode($iller);
		exit();
	}

	function ilceleriVer()
	{
		header('Content-Type: application/json');
		$query = " SELECT
								*
						FROM
								" . DB_PRFX . "_ilceler";
		$ilceler = $this->db->get_results($query);
		echo json_encode($ilceler);
		exit();
	}


	function okulTurleriniVer()
	{
		header('Content-Type: application/json');
		$query = " SELECT
								*
						FROM
								" . DB_PRFX . "_okul_turleri";
		$okul_turleri = $this->db->get_results($query);
		echo json_encode($okul_turleri);
		exit();
	}


}