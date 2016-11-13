<?php
class frontpage_mdl extends BaseModel{
	function __construct($parent){
		parent::__construct($parent);
	}

	function ajaxGetKelimeOnerileri(){
		$kart = $_POST['kart'];
		$eldeki_harfler = $_POST['eldeki_harfler'];
		$kelimelik = new kelimelik($kart,$eldeki_harfler);
		$kelimeleri_hazirla = new kelimeleriHazirla($kelimelik->kalip_icin_temel_gruplar);
		$kelimeler_arr =  $kelimeleri_hazirla->sonuc_arr;
		$kurallari_uygula = new kurallariUygula($kelimeler_arr);
		exit();
	}

}



////////////////////////////////////////////////////MANTIK SINIFI//////////////
/**
 * Class kelimelik
 */
class kelimelik
{
	var $kalip_icin_temel_gruplar = array();
	var $regexler_arr=array();
	var $x=1;
	var $y=0;
	var $eldeki_harfler;

	/**
	 * @param $kart
	 * @param $eldeki_harfler
	 */
	function kelimelik($kart,$eldeki_harfler)
	{
		$this->eldeki_harfler = $eldeki_harfler;
		#satırlardaki kalıp çıkartma işlemi
		#baştan sona sondan başa full tarama
		foreach ($kart as $key=>$satir_arr) {
			$this->y=$key;
			$this->coz($satir_arr);
		}

		$this->tekrarEdenKaliplariTemizle();// regex temelleri bakımından aynı olanları
		$this->bosKaliplariTemizle();
		$this->uzunKaliplariTemizle();
		$this->regexPatternleriniOlustu();
		echo json_encode($this->kalip_icin_temel_gruplar);
	}

	/**
	 * @param $satir_arr
	 */
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
					$konum 									= array(count($satir_arr) - ($key + 1)+$this->x,$this->y);
					$this->kalip_icin_temel_gruplar[] 		= array($konum,array_slice($satir_arr, count($satir_arr) - ($key + 1), $key + 1));
				}
			} else {
				$konum									=  array(count($satir_arr) - ($key + 1)+$this->x,$this->y);
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
					$konum								= array($this->x,$this->y);
					$this->kalip_icin_temel_gruplar[] 	= array($konum,array_slice($satir_arr, 0, $key + 1));
				}
			} else {
				$konum									= array($this->x,$this->y);
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
						$this->x                = $this->x+$key-1;
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
						$son_kirpma_konumu 		= count($basi_sonu_temiz_satir_rvrs)-($key+1);
						$this->x                = $this->x+$key;
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

	/**
	 *
	 */
	function tekrarEdenKaliplariTemizle(){
		$temel_kaliplar_arr = $this->kalip_icin_temel_gruplar;
		foreach ( $temel_kaliplar_arr as $key=>$temel_grup ) {
			if($temel_grup[1][0]==''){
				array_shift($temel_grup[1]);
				$this->aynisiVarsaKalibiSil($temel_grup[1]);
			}
		}
		$temel_kaliplar_arr = $this->kalip_icin_temel_gruplar;
		foreach ( $temel_kaliplar_arr as $key=>$temel_grup ) {
			if($temel_grup[1][count($temel_grup[1])]==''){
				array_pop($temel_grup[1]);
				$this->aynisiVarsaKalibiSil($temel_grup[1]);
			}
		}
		$temel_kaliplar_arr = $this->kalip_icin_temel_gruplar;
		foreach ( $temel_kaliplar_arr as $key=>$temel_grup ) {
			if($temel_grup[1][count($temel_grup[1])]==''){
				array_pop($temel_grup[1]);
			}
			if($temel_grup[1][0]==''){
				array_shift($temel_grup[1]);
			}
			$this->aynisiVarsaKalibiSil($temel_grup[1]);
		}


	}

	/**
	 * @param $kontrol_icin_arr
	 */
	function aynisiVarsaKalibiSil($kontrol_icin_arr){
		$temel_kaliplar_arr = $this->kalip_icin_temel_gruplar;
		foreach ( $temel_kaliplar_arr as $key=>$temel_grup ) {
			if($temel_grup[1]==$kontrol_icin_arr){
				unset($this->kalip_icin_temel_gruplar[$key]);
			}
		}
	}

	/**
	 *
	 */
	function bosKaliplariTemizle(){
		$temel_kaliplar_arr = $this->kalip_icin_temel_gruplar;
		foreach ( $temel_kaliplar_arr as $key0=>$temel_grup ) {
			$item_dolu = false;
			foreach ( $temel_grup[1] as $key1=>$harf ) {
				if($harf!=''){
					$item_dolu = true;
				}
			}
			if(!$item_dolu){
				unset($this->kalip_icin_temel_gruplar[$key0]);
			}
		}
	}

	/**
	 *
	 */
	function uzunKaliplariTemizle(){
		$temel_kaliplar_arr = $this->kalip_icin_temel_gruplar;
		foreach ( $temel_kaliplar_arr as $key0=>$temel_grup ) {
			$basi_sonu_temiz_satir  = $this->trimFirstLast($temel_grup[1])[0];
			$dolu_item_sayisi       = $this->doluItemSayisi($basi_sonu_temiz_satir);
			$kalip_boyu             = count($basi_sonu_temiz_satir);
			$bos_alan               = $kalip_boyu - $dolu_item_sayisi;
			if($bos_alan==0){
				continue;
			}
			if($bos_alan>strlen($this->eldeki_harfler)){
				unset($this->kalip_icin_temel_gruplar[$key0]);
			}
		}
	}


	/**
	 * @param $basi_sonu_temiz_satir
	 */
	function doluItemSayisi($basi_sonu_temiz_satir){
		$harf_sayisi =0 ;
		foreach ( $basi_sonu_temiz_satir as $key=>$item ) {
			if($item!=''){
				$harf_sayisi++;
			}
		}
		return $harf_sayisi;
	}

	/**
	 *
	 */
	function regexPatternleriniOlustu(){
		$regex_kalip='';
		$max=0;
		// önce baştaki kalıbı  oluştur
		$temel_kaliplar_arr = $this->kalip_icin_temel_gruplar;
		foreach ( $temel_kaliplar_arr as $key0=>$temel_grup ) {
			foreach ( $temel_grup[1] as $key1=>$harf ) {
				if($harf==''){
					$max++;
				}else{
					if(strlen($regex_kalip)){
						if($max==0){
							$regex_kalip .=$harf;
						}else{
							$regex_kalip .="[$this->eldeki_harfler]{{$max}}".$harf;
						}
					}else{
						if($max==0){
							$regex_kalip .=$harf;
						}else{
							$regex_kalip .="[$this->eldeki_harfler]{0,$max}".$harf;
						}
					}
					$max=0;
				}
			}
			$regex_kalip                                    .="[$this->eldeki_harfler]{0,$max}";
			$this->kalip_icin_temel_gruplar[$key0]['regex'] = $regex_kalip;
			$max                                            =0;
			$regex_kalip                                    ='';
		}
	}

}




////////////////////////////VERİ ÇEKME SINIFI /////////////////

class kelimeleriHazirla{
	var $db;
	var $sonuc_arr = array();
	/**
	 * @param $kalip_icin_temel_gruplar
	 */
	function kelimeleriHazirla($kalip_icin_temel_gruplar){
		global $db;
		$this->db = $db;
		$this->getKelimeler($kalip_icin_temel_gruplar);
	}

	function getKelimeler($kalip_icin_temel_gruplar){
		$kelimeler_arr = [];
		foreach ($kalip_icin_temel_gruplar as $kalip) {
			$regex      = $kalip['regex'];
			$sql        = "SELECT HEAD_MULT FROM kelimeler WHERE HEAD_MULT REGEXP '$regex'";
			$kelimeler_arr[]      = $this->db->get_results($sql);
		}
		$this->sonuc_arr = $kelimeler_arr ;
	}
}





// //////////////////////////////// KURALLARI UYGULA ///////////////////////

class kurallariUygula{

}