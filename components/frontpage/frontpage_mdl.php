<?php
class frontpage_mdl extends BaseModel{
	function __construct($parent){
		parent::__construct($parent);
	}

	function ajaxGetKelimeOnerileri(){
		$kart 				= $_POST['kart'];
		$eldeki_harfler 	= $_POST['eldeki_harfler'];
		// normal tablonun kelimeleirni bulalım
		$kelimelik 			= new kelimelik($kart,$eldeki_harfler);
		$kelimeler_hazirla 	= new kelimeleriHazirla($kelimelik->kalip_icin_temel_gruplar);
		$kalip_icin_temel_gruplar	= $kelimeler_hazirla->kalip_icin_temel_gruplar;
		$normal_tablo_sonuclari 	= new kurallariUygula($kalip_icin_temel_gruplar,$eldeki_harfler);
		$normal_tablo_sonuclari = $normal_tablo_sonuclari->kalip_icin_temel_gruplar;

		// sola dönderilmiş tablonun kelimeleirni bulalım
		$kart_sola_donuk    = $this->kartiSolaDonder($kart);
		$_POST['kart']		= $kart_sola_donuk;
		$kelimelik 			= new kelimelik($kart_sola_donuk,$eldeki_harfler);
		$kelimeler_hazirla 	= new kelimeleriHazirla($kelimelik->kalip_icin_temel_gruplar);
		$kalip_icin_temel_gruplar	= $kelimeler_hazirla->kalip_icin_temel_gruplar;
		$sola_donuk_tablo_sonuclari 	= new kurallariUygula($kalip_icin_temel_gruplar,$eldeki_harfler);
		$sola_donuk_tablo_sonuclari     = $sola_donuk_tablo_sonuclari->kalip_icin_temel_gruplar;

		echo json_encode(array($normal_tablo_sonuclari,$sola_donuk_tablo_sonuclari));
		exit();
	}

	function kartiSolaDonder($kart){
		$yeni_kart = array();
		for($a=0;$a<count($kart);$a++){//satırlar
			for($b=count($kart[$a])-1;$b>=0;$b--){//sütunlar
				$x_yeni = (count($kart[$a])-1) - $b;
				$y_yeni = $a;
				$yeni_kart[$x_yeni][$y_yeni]=$kart[$a][$b];
			}
		}
		//asort($yeni_kart);
		return $yeni_kart;
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
	var $x=0;
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
			$this->x=0;
			$this->coz($satir_arr);
		}

		$this->tekrarEdenKaliplariTemizle();// regex temelleri bakımından aynı olanları
		$this->bosKaliplariTemizle();
		$this->uzunKaliplariTemizle();
		$this->regexPatternleriniOlustu();
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
					$konum 									= array("sutun"=>count($satir_arr) - ($key+1)+$this->x,"satir"=>$this->y);
					$this->kalip_icin_temel_gruplar[] 		= array("konum"=>$konum,"kalip"=>array_slice($satir_arr, count($satir_arr) - ($key + 1), $key + 1));
				}
			} else {
				$konum									=  array("sutun"=>count($satir_arr) - ($key+1)+$this->x,"satir"=>$this->y);
				$this->kalip_icin_temel_gruplar[] 		=  array("konum"=>$konum,"kalip"=>array_slice($satir_arr, count($satir_arr) - ($key + 1), $key + 1));
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
					$konum								= array("sutun"=>$this->x,"satir"=>$this->y);
					$this->kalip_icin_temel_gruplar[] 	= array("konum"=>$konum,"kalip"=>array_slice($satir_arr, 0, $key + 1));
				}
			} else {
				$konum									= array("sutun"=>$this->x,"satir"=>$this->y);
				$this->kalip_icin_temel_gruplar[] 		= array("konum"=>$konum,"kalip"=>array_slice($satir_arr, 0, $key + 1));
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
		$konum 									= array("sutun"=>$this->x,"satir"=>$this->y);
		$this->kalip_icin_temel_gruplar[] 		= array("konum"=>$konum,"kalip"=>$kirpilmis_alt_satir);
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
			if($temel_grup['kalip'][0]==''){
				array_shift($temel_grup['kalip']);
				$this->aynisiVarsaKalibiSil($temel_grup['kalip'],$temel_grup['konum']['satir']);
			}
		}
		$temel_kaliplar_arr = $this->kalip_icin_temel_gruplar;
		foreach ( $temel_kaliplar_arr as $key=>$temel_grup ) {
			if($temel_grup['kalip'][count($temel_grup['kalip'])]==''){
				array_pop($temel_grup['kalip']);
				$this->aynisiVarsaKalibiSil($temel_grup['kalip'],$temel_grup['konum']['satir']);
			}
		}
		$temel_kaliplar_arr = $this->kalip_icin_temel_gruplar;
		foreach ( $temel_kaliplar_arr as $key=>$temel_grup ) {
			if($temel_grup['kalip'][count($temel_grup['kalip'])]==''){
				array_pop($temel_grup['kalip']);
			}
			if($temel_grup['kalip'][0]==''){
				array_shift($temel_grup['kalip']);
			}
			$this->aynisiVarsaKalibiSil($temel_grup['kalip'],$temel_grup['konum']['satir']);
		}


	}

	/**
	 * @param $kontrol_icin_arr
	 */
	function aynisiVarsaKalibiSil($kontrol_icin_arr,$satir){
		$temel_kaliplar_arr = $this->kalip_icin_temel_gruplar;
		foreach ( $temel_kaliplar_arr as $key=>$temel_grup ) {
			if($temel_grup['kalip']==$kontrol_icin_arr){
				if($temel_grup['konum']['satir']==$satir){
					unset($this->kalip_icin_temel_gruplar[$key]);
				}
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
			foreach ( $temel_grup['kalip'] as $key1=>$harf ) {
				if($harf!=''){
					$item_dolu = true;
					$this->kalip_icin_temel_gruplar[$key0]['harfler'] = implode('',$temel_grup['kalip']);
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
			$basi_sonu_temiz_satir  = $this->trimFirstLast($temel_grup['kalip'])[0];
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
			foreach ( $temel_grup['kalip'] as $key1=>$harf ) {
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
			if($max){
				$regex_kalip .="[$this->eldeki_harfler]{0,$max}";
			}
			$this->kalip_icin_temel_gruplar[$key0]['regex'] = $regex_kalip;
			$max                                            =0;
			$regex_kalip                                    ='';
		}
	}

}


////////////////////////////VERİ ÇEKME SINIFI /////////////////

class kelimeleriHazirla{
	var $db;
	var $kalip_icin_temel_gruplar;
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
		foreach ($kalip_icin_temel_gruplar as $key=>$kalip) {
			//kalıp içindeki paçaları ulmamalı mesala * * * * ara * *aşk * *    içindeki ara ve aşk ı bulmamalı
			$regex      = '^'.$kalip['regex'].'$';
			$sql        = "SELECT HEAD_MULT FROM kelimeler WHERE HEAD_MULT REGEXP '$regex' AND length(HEAD_MULT)>2";
			$kalip_icin_temel_gruplar[$key]['kelimeler']    = $this->db->get_results($sql);
		}
		$this->kalip_icin_temel_gruplar =  $kalip_icin_temel_gruplar;
	}
}



// //////////////////////////////// KURALLARI UYGULA ///////////////////////

class kurallariUygula{
	var $kalip_icin_temel_gruplar,$eldeki_harfler;
	function kurallariUygula($kalip_icin_temel_gruplar,$eldeki_harfler){
		$this->kalip_icin_temel_gruplar     =   $kalip_icin_temel_gruplar;
		$this->eldeki_harfler               = $eldeki_harfler;
		$this->kural_01_harfler_yeterlimi();
		$this->kural_02_yenikelimeler_sozlukte_varmi();
	}

	function str_split_unicode($str, $l = 0) {
		if ($l > 0) {
			$ret = array();
			$len = mb_strlen($str, "UTF-8");
			for ($i = 0; $i < $len; $i += $l) {
				$ret[] = mb_substr($str, $i, $l, "UTF-8");
			}
			return $ret;
		}
		return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
	}

	function kural_01_harfler_yeterlimi(){
		$kalip_icin_temel_gruplar_copy = $this->kalip_icin_temel_gruplar;
		foreach($kalip_icin_temel_gruplar_copy as $key0=>$grup_arr){
			$kelimeler      = $grup_arr['kelimeler'];
			foreach($kelimeler as $key1=>$kelime){
				$bulunan_kelime_arr     = $this->str_split_unicode(strtolower($kelime->HEAD_MULT));
				$harfler				= $this->eldeki_harfler.$grup_arr['harfler'];
				$eldeki_harfler_arr		= $this->str_split_unicode($harfler);
				//$bulunan_kelime_arr_copy = $bulunan_kelime_arr;
				$eldeki_harfler_arr_copy = $eldeki_harfler_arr;
				foreach($eldeki_harfler_arr_copy as $key2=>$harf){
					if(($key3 = array_search(strtolower($harf), $bulunan_kelime_arr)) !== false) {
						unset($eldeki_harfler_arr[$key2]);
						unset($bulunan_kelime_arr[$key3]);
					}
				}
				if(count($bulunan_kelime_arr)){
					unset($this->kalip_icin_temel_gruplar[$key0]['kelimeler'][$key1]);
				}
			}
		}
	}


	/**
	 *
	 */
	function kural_02_yenikelimeler_sozlukte_varmi(){
		global $db;
		$kart = $_POST['kart'];
		$kalip_icin_temel_gruplar_copy = $this->kalip_icin_temel_gruplar;
		foreach($kalip_icin_temel_gruplar_copy as $key0=>$grup_arr) {
			$kelimeler  = $grup_arr['kelimeler'];
			$kalip_arr  = $grup_arr['kalip'];
			foreach ($kelimeler as $key1 => $kelime) {
				$bulunan_kelime_arr        = $this->str_split_unicode(strtolower($kelime->HEAD_MULT));
				$kaliba_oturmus_arr        = $this->kalibaOturmaKonumu($kalip_arr,$bulunan_kelime_arr);
				$this->kalip_icin_temel_gruplar[$key0]['kelimeler'][$key1]->kaliba_oturmus_hali=$kaliba_oturmus_arr;
				$harfin_konumu=0;
				foreach($kaliba_oturmus_arr as $key3=>$harf){
					if($harf){
						$harfin_konumu = $key3;
						if($kalip_arr[$key3]==''){
							// harfin olduğu kaılıbın boş olduğu yer yeni harf vardır altına üstüne bakılacak
							$sutun          = $grup_arr['konum']['sutun']+$harfin_konumu;
							if(!isset($this->kalip_icin_temel_gruplar[$key0]['kelimeler'][$key1]->global_sutun_no)){
								$this->kalip_icin_temel_gruplar[$key0]['kelimeler'][$key1]->global_sutun_no = $sutun;
							}
							$yeni_kelime    = $this->olusanYeniKelime($sutun,$grup_arr['konum']['satir'],$harf,$kart);
							if($yeni_kelime){
								$sql        = "SELECT HEAD_MULT FROM kelimeler WHERE HEAD_MULT = '$yeni_kelime'";
								if(!count($db->get_results($sql))){
									unset($this->kalip_icin_temel_gruplar[$key0]['kelimeler'][$key1]);
								}
							}
						}
					}
				}
				// bulunan kelime kalıpile aynıysa sil gitsin ç özelikle iki harflilerde çokça oluyor
				// kalıp * * de * *  bulunan kelime  "de"  buduru istenmeyen durum de bu tür kelimeler silinmeli
				if(!isset($this->kalip_icin_temel_gruplar[$key0]['kelimeler'][$key1]->global_sutun_no)){
					unset($this->kalip_icin_temel_gruplar[$key0]['kelimeler'][$key1]);
				}
			}
		}
	}





	function olusanYeniKelime($sutun,$satir,$harf,$kart){
		if($sutun<3 && $satir>0){
			$stop=0;
		}
		$ust_satir=$alt_satir=$satir;
		$ust_parca  = '';
		$alt_parca  = '';
		$ust_bitti  = false;
		$alt_bitti  = false;
		//altı üstü boşsa false dönder
		$yeni_kelime='';
		$aralik = 0;
		while(!$yeni_kelime){
			$aralik++;
			$ust_satir = $satir-$aralik;
			$alt_satir = $satir+$aralik;
			//üst tarafda harf varmi
			if(isset($kart[$ust_satir][$sutun]) && $kart[$ust_satir][$sutun]!=''){
				$ust_parca = $kart[$ust_satir][$sutun].$ust_parca;

			}else{
				$ust_bitti = true;
			}
			//alt tarafta varmı
			if(isset($kart[$alt_satir][$sutun]) && $kart[$alt_satir][$sutun]!=''){
				$alt_parca = $kart[$alt_satir][$sutun].$alt_parca;
			}else{
				$alt_bitti = true;
			}

			if($alt_bitti && $ust_bitti){
				$yeni_kelime = $ust_parca . $harf . $alt_parca;
				if(strlen($yeni_kelime)>1){
					return $yeni_kelime;
				}else{
					return false;
				}
				//dönmemeli
				//veritabanında bakmalı bu kelime tabloda varmı
			}
		}






	}

	/**
	 * @param $kalip_arr
	 * @param $bulunan_kelime_arr
	 * @return string
	 */
	function kalibaOturmaKonumu($kalip_arr,$bulunan_kelime_arr){
		$tara						= true;
		$sonuc = '';
		$max_eslesme =0;
		while($tara){
			$eslesen_harf_sayisi=0;
			$a=0;
			foreach ($bulunan_kelime_arr as $key1 => $harf) {
				if($harf==$kalip_arr[$key1] && $harf && $kalip_arr[$key1]){
					$a++;
					$eslesen_harf_sayisi = $a;
				}
			}
			//if($eslesen_harf_sayisi_onceki>$eslesen_harf_sayisi){
			if($max_eslesme<$eslesen_harf_sayisi){
					$max_eslesme = $eslesen_harf_sayisi;
					$sonuc = $bulunan_kelime_arr;
				}
			//}
			if(count($bulunan_kelime_arr)==count($kalip_arr)){
				//$tara	= false;
				array_shift($bulunan_kelime_arr);
				return $sonuc;
			}
			array_unshift($bulunan_kelime_arr,'');
		}

	}
}