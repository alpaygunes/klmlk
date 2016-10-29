<?php
class frontpage_mdl extends BaseModel{
	function __construct($parent){
		parent::__construct($parent);
	}

	function ajaxGetKelimeOnerileri(){
		$kart = $_POST['kart'];
		$kelimelik = new kelimelik($kart);
		exit();
	}

}



/////////////////////////////////////////////////////MANTI SINIFI//////////////
/**
 * Class kelimelik
 */
class kelimelik
{
	var $kalip_icin_temel_gruplar = array();
	var $x=0;
	var $y=0;

	function kelimelik($kart)
	{
		#satırlardaki kalıp çıkartma işlemi
		#baştan sona sondan başa full tarama
		foreach ($kart as $key=>$satir_arr) {
			$this->y=$key;
			$this->coz($satir_arr);
		}

		echo json_encode($this->kalip_icin_temel_gruplar);
	}

	function coz($satir_arr){
		$string = implode('', $satir_arr);
		if (strlen($string)) {
			$this->tamSatirKaliplariniOlustur($satir_arr);
			$this->altSatirKaliplariniOlust($satir_arr);
		}
	}

	/**
	 * @param $ilk_harf_konumu
	 * @param $son_harf_konumu
	 * @param $satir_arr
	 */
	private function tamSatirKaliplariniOlustur($satir_arr)
	{
		$satir_arr_rvrs = array_reverse($satir_arr);
		#sondan başa doğru
		foreach ($satir_arr_rvrs as $key => $harf) {
			#öncesinde harf varmı ?
			if (isset($satir_arr_rvrs[($key + 1)])) {
				if (strlen($satir_arr_rvrs[($key + 1)])) {
					#öncesinde hafr var ise. Bu bir harf grubudur. sıradakine geç
					continue;
				} else {
					$konum 									= array(count($satir_arr) - ($key + 1),$this->y);
					$this->kalip_icin_temel_gruplar[] 		= array($konum,array_slice($satir_arr, count($satir_arr) - ($key + 1), $key + 1));
				}
			} else {
				$konum									=  array(count($satir_arr) - ($key + 1),$this->y);
				$this->kalip_icin_temel_gruplar[] 		=  array($konum,array_slice($satir_arr, count($satir_arr) - ($key + 1), $key + 1));
			}

		}

		#baştan sona doğru
		foreach ($satir_arr as $key => $harf) {
			#sonrasında harf varmı ?
			if (isset($satir_arr[($key + 1)])) {
				if (strlen($satir_arr[($key + 1)])) {
					#sonrasında harf var ise. Bu bir harf grubudur. sıradakine geç
					continue;
				} else {
					$konum								= array($key,$this->y);
					$this->kalip_icin_temel_gruplar[] 	= array($konum,array_slice($satir_arr, 0, $key + 1));
				}
			} else {
				$konum									= array($key,$this->y);
				$this->kalip_icin_temel_gruplar[] 		= array($konum,array_slice($satir_arr, 0, $key + 1));
			}
		}
	}

	/**
	 * @param $satir_arr
	 */
	function altSatirKaliplariniOlust($satir_arr){
		#satır içindeki alt parçaları almak için baştaki ve sonradaki boşlukları silelim
		$temiz_satir_arr = $this->trimFirstLast($satir_arr);
		$basi_sonu_temiz_satir  = $temiz_satir_arr[0];
		$ilk_harf_konumu        = $temiz_satir_arr[1];
		$son_harf_konumu        = $temiz_satir_arr[2];
		$this->x                = $this->x+$ilk_harf_konumu;


		#ilk ve son anlamlı grubu çıkartalım
		$ilk_kirpma_konumu =0;
		foreach ($basi_sonu_temiz_satir as $key => $harf) {
			if($harf==''){
				if (isset($basi_sonu_temiz_satir[($key + 1)])) {
					if (strlen($basi_sonu_temiz_satir[($key + 1)])==0) {
						$ilk_kirpma_konumu = $key+1;
						//burada kaldın . Alt kalıpların konumları hesaplanacak. global konumları yanlış oluyor
						$this->x                = $this->x+$ilk_harf_konumu;
						break;
					}
				}else{
					# key +1 yok ise array bitmiş demektir.
					# gerekini sonra yapmam lazım
				}
			}
		}

		$son_kirpma_konumu =0;
		$basi_sonu_temiz_satir_rvrs = array_reverse($basi_sonu_temiz_satir);
		foreach ($basi_sonu_temiz_satir_rvrs as $key => $harf) {
			if($harf==''){
				if (isset($basi_sonu_temiz_satir_rvrs[($key + 1)])) {
					if (strlen($basi_sonu_temiz_satir_rvrs[($key + 1)])==0) {
						$son_kirpma_konumu = count($basi_sonu_temiz_satir_rvrs)-($key+1);
						break;
					}
				}else{
					# key +1 yok ise array bitmiş demektir.
					# gerekini sonra yapmam lazım
				}
			}
		}

		if($son_kirpma_konumu==0 && $ilk_kirpma_konumu==0){
			return;
		}
		$kirpilmis_alt_satir 					= array_slice($basi_sonu_temiz_satir, $ilk_kirpma_konumu, $son_kirpma_konumu-$ilk_kirpma_konumu);
		$konum 									= array($this->x,$this->y);
		$this->kalip_icin_temel_gruplar[] 		= array($konum,$kirpilmis_alt_satir);
		$this->coz($kirpilmis_alt_satir);
	}

	/**
	 * @param $satir_arr
	 * @return array
	 */
	function trimFirstLast($satir_arr){
		$ilk_harf_konumu = 0;
		$son_harf_konumu = 0;
		foreach ($satir_arr as $key => $harf) {
			if ($harf) {
				$ilk_harf_konumu = $key;
				break;
			}
		}

		$satir_arr_rvrs = array_reverse($satir_arr);
		foreach ($satir_arr_rvrs as $key => $harf) {
			if ($harf) {
				$son_harf_konumu = $key;
				break;
			}
		}
		$satir_arr = array_slice($satir_arr,$ilk_harf_konumu,(count($satir_arr)-(+$ilk_harf_konumu+$son_harf_konumu)));
		return array($satir_arr,$ilk_harf_konumu,$son_harf_konumu);
	}
}