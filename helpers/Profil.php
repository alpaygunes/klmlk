<?php
/**
 * Created by PhpStorm.
 * User: alpay
 * Date: 28.06.2015
 * Time: 11:19
 */

class Profil {
	var $user_id;
	var $username;
	var $BT_table_name;
	var $db;

	function instance($portal){
		global $db;
		$this->user_id          =  $portal->user_id;
		$this->username         =  $portal->username;
		$this->BT_table_name    =  DB_PRFX."_profil";
		$this->db               =  $db;
		return $this->getProfilData();
	}

	function getProfilData(){
		if($this->user_id==null){
			return null;
		}

		$query 		= " SELECT
							*
						FROM
							$this->BT_table_name
						WHERE
							user_id = ".$this->user_id;
		$profil	    = $this->db->get_results($query);
		$profil     = $this->getIlveIlceAdi($profil);
		$profil['0']->username = $this->username;
		// kayıtlı üyemi
		if($profil && $this->user_id>0){
			$profil['0']->kayitli = true;
		}else{
			$profil['0']->kayitli = false;
		}

		if($profil)
		{
			return $profil;
		}
		else
		{
			return null;
		}
	}

	function getIlveIlceAdi($profil){
		$query      = " SELECT
							IL_ADI
						FROM
							".DB_PRFX."_iller
						WHERE
							 IL_ID=".$profil[0]->il_id;
		$profil[0]->il_adi  = $this->db->get_var($query);
		$query      = " SELECT
							ILCE_ADI
						FROM
							".DB_PRFX."_ilceler
						WHERE
							 ILCE_ID=".$profil[0]->ilce_id;
		$profil[0]->ilce_adi  = $this->db->get_var($query);
		$query      = " SELECT
							takma_ad
						FROM
							".DB_PRFX."_gorevler
						WHERE
							 id=".$profil[0]->gorev_adi;
		$profil[0]->gorevi  = $this->db->get_var($query);
		return $profil;
	}

	function getUserProfil($user_id){
		global $db;
		$this->user_id          =  $user_id;
		$this->BT_table_name    =  DB_PRFX."_profil";
		$this->db               =  $db;
		$profil                 =  $this->getProfilData();

		$user					= new User();
		$user                   = $user->getUserByID($user_id);
		$profil['0']->username  = $user->username;
		return $profil;
	}

}