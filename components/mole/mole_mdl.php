<?php
class mole_mdl extends BaseModel{
	function __construct($parent){
		parent::__construct($parent);
	}

	function gorevListele(){
		$istemcinin_adi      = $this->request['istemcinin_adi'];
		$istemcinin_IP       = $this->get_client_ip();
		echo json_encode($_REQUEST);
		exit();
	}

	function get_client_ip() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	function aktiflikZamaniniGuncelle(){
		$istemci_adi      = $this->request['istemcinin_adi'];
		$istemci_IP       = $this->get_client_ip();

		// zaten db de varsa güncelle , yoksa yeni kayıt olacak
		$query 								= " SELECT
 														*
 											  	FROM "
														. DB_PRFX. "_mole_istemciler
												WHERE
														  istemci_adi = '$istemci_adi' ";
		$sonuc =  $this->db->get_results($query);

		if(count($sonuc)){
			$query      =" UPDATE "
								.DB_PRFX."_mole_istemciler
							SET
								istemci_ip          = '$istemci_IP',
								son_erisim_zamani   = NOW()
							WHERE
								istemci_adi         ='".$istemci_adi."'";
		}else{
			$query      = "INSERT INTO "
									.DB_PRFX."_mole_istemciler
							  VALUES
							        ('','$istemci_IP','$istemci_adi',NOW())";
		}
		$this->db->query($query);
	}
}