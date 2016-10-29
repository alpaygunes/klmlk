<?php
class Sinav {
    var $sinavTbl;//veritabanındaki veriyi saklar
    var $sinav_no ;
    var $db;
    var $sinava_girmis_kullanici_sayisi=0;
    var $cevap_kagitlari;
    var $sirali_sonuc_kagitlariArr;
    var $kitapcik_idleriStr;
    var $soru_idleriArr;
    var $soru_sayisi;
    var $sorularTbl;

    function instance($sinav_no){
        global $db;
        $this->sinav_no                         = $sinav_no;
        $this->db                               = $db;
        $this->sinavverileri();
        $this->soruIdleriniGetir();
        $this->cevapKagitlariniGetir();
        $this->sorulariGetir();
        $this->siraliListeyiOlustur();
    }

    private function sinavverileri(){
        $query                      = " SELECT
                                            *
                                        FROM
                                              ".DB_PRFX."_sb_sinavlar
                                        WHERE
                                                id=".$this->sinav_no;
                                                //." AND sahip_id=".ExternalSiteConnector::$user_id;
            $sonuc                      = $this->db->get_results($query);
        $this->kitapcik_idleriStr  = $sonuc[0]->kitapcik_idleri;
        $this->sinavTbl = $sonuc;
    }

    private function cevapKagitlariniGetir(){
        global $CORE;



        $query 				= "SELECT
									*
								FROM
									".DB_PRFX."_sb_cevap_kagitlari
								WHERE
									sinav_no=".$this->sinav_no;

        $kagitlar			= $this->db->get_results($query);
        //kayıt sayısını alalım
//        $CORE->pagination->total_item_count =  count($kagitlar);
//        $CORE->pagination->initialize();
//        $start				= $CORE->pagination->limit_start;
//        $item_per_page		= $CORE->pagination->item_per_page;
//        $limit 				= " LIMIT $start,$item_per_page";
//
//        $query                  = " SELECT
//                                        *
//                                    FROM
//                                        ".DB_PRFX."_sb_cevap_kagitlari
//                                    WHERE
//                                        sinav_no=".$this->sinav_no .
//                                    $limit;



        $this->cevap_kagitlari                      = $kagitlar;//$this->db->get_results($query);
        $this->sinava_girmis_kullanici_sayisi       = count($kagitlar);
    }

    private function soruIdleriniGetir(){
        $sorularStr                 = '';
        $kitapciklarArr             = explode(',',$this->kitapcik_idleriStr);
        foreach ($kitapciklarArr as $kitapcik) {
            $query					="	SELECT
											*
										FROM "
                .DB_PRFX."_sb_kitapciklar
										WHERE
											id=".$kitapcik;
            $kitapcik				= $this->db->get_results($query);
            $sorularStr				.= ",".$kitapcik[0]->sorular;
        }
        $sorularArr				= explode(",", $sorularStr);
        array_shift ($sorularArr);
        $this->soru_sayisi		= count($sorularArr);
        $this->soru_idleriArr	= $sorularArr;
    }

    private function sorulariGetir() {
        $sorular_idStr      = implode(',',$this->soru_idleriArr);
        $query              = " SELECT
                                        id,cevap
                                FROM
                                        ".DB_PRFX."_sb_sorular
                                WHERE
                                        id IN($sorular_idStr)
                                ORDER BY
                                        FIELD(id, $sorular_idStr)
                                ";
        $this->sorularTbl   = $this->db->get_results($query);
    }

    private function siraliListeyiOlustur(){
        $sirali_dizi_arr        = array();
        if(count($this->cevap_kagitlari)){
            foreach ($this->cevap_kagitlari as $kagit) {
                $user_id        = $kagit->user_id;
                $guncel_ogrenci = $this->ogrencininSonucKagidiniGetir($user_id);
                if($guncel_ogrenci===false){
                    continue;
                }
                $guncel_ogrenci['cevap_kagidi_id'] = $kagit->id;
                if(count($sirali_dizi_arr)){
                    $sayac      = 0;
                    $eklendi    = false;
                    foreach ($sirali_dizi_arr as $value) {
                        if($guncel_ogrenci['net_sayisi']> $value['net_sayisi']){
                            //array_splice($sirali_dizi_arr, $sayac, 0, $guncel_ogrenci);
                            $arr1               = array_slice($sirali_dizi_arr,0,$sayac);
                            $arr2               = array_slice($sirali_dizi_arr,($sayac));
                            $arr1[]             = $guncel_ogrenci;
                            $sirali_dizi_arr    = array_merge($arr1,$arr2);
                            $eklendi            = true;
                            break;
                        }
                        $sayac++;
                    }
                    if(!$eklendi){
                        $sirali_dizi_arr[]  =$guncel_ogrenci;
                    }
                }else{
                    $sirali_dizi_arr[]      =$guncel_ogrenci;
                }
            }
        }
        $this->sirali_sonuc_kagitlariArr= $sirali_dizi_arr;
    }

    function ogrencininSonucKagidiniGetir($ogrenci_id){

        $query                  = " SELECT
                                            *
                                    FROM
                                            ".DB_PRFX."_sb_cevap_kagitlari
                                    WHERE
                                            sinav_no=".$this->sinav_no
                                            . ' AND user_id ='."'".$ogrenci_id."'";
        $ogrencinin_cevap_kagidi        = $this->db->get_results($query);
        //cevağ kağıdından öğrencinin cevaplarını alalım
        $soru_id_yanit_ikili_dizisi     = explode('#',$ogrencinin_cevap_kagidi[0]->cevaplar);
        $ogrenci_cevabi_arr= array();//soru_id key cavap value olacak
        foreach ($soru_id_yanit_ikili_dizisi as $soru_id_yanit) {
            $parcalarArr        = explode('_',$soru_id_yanit);
            $ogrenci_cevabi_arr[$parcalarArr[0]]=$parcalarArr[1];
        }

        // doğru yanlış boş değerlendirmesi
        // soru_id - dogrucevap - ogrencinin_yaniti - sonuc (boş ,yanlış ,doğru an biri)
        $toplu_sonuc_dizisi_arr = array();
        foreach ($this->sorularTbl as $soru) {
            $soru_sonuc_dizisi_arr  = array();
            $soru_id                = $soru->id;
            $yanit                  = null;
            $sonuc                  = '-';

            // yanıt  boşmu , varsa doğrumu yanlışmı
            if (isset($ogrenci_cevabi_arr[$soru_id])) {
                $yanit = strtoupper($ogrenci_cevabi_arr[$soru_id]);
                if ($yanit == $soru->cevap) {
                    $sonuc = "1"; // yani doğru
                } else if($yanit=='') {
                    $sonuc = ""; // boş
                }else{
                    $sonuc = "0"; // yani yanlış
                }
            }

            //$soru_sonuc_dizisi_arr['soru_id']   = $soru_id;
            $soru_sonuc_dizisi_arr['cevap']     = $soru->cevap;
            $soru_sonuc_dizisi_arr['yanit']     = $yanit;
            $soru_sonuc_dizisi_arr['sonuc']     = $sonuc;
            $toplu_sonuc_dizisi_arr[$soru_id]   = $soru_sonuc_dizisi_arr;
        }

        //doğru yanlış sonuçlarını alalım
        $dogru_sayisi   =0;
        $yanlis_sayisi  =0;
        $bos_sayisi     =0;
        foreach ($toplu_sonuc_dizisi_arr as $sonuc){
            if($sonuc['sonuc']=="1"){
                $dogru_sayisi++;
            }elseif($sonuc['sonuc']=="0"){
                $yanlis_sayisi++;
            }else{
                $bos_sayisi++;
            }
        }

        // eğer doğru hiç yoksa bu öğrenincin kağıdını e geç
        if($dogru_sayisi==0){
            return false;
        }


        if($this->sinavTbl[0]->yanlis_dogru_orani>0){
            $karne['net_sayisi'] = $dogru_sayisi-($yanlis_sayisi/$this->sinavTbl[0]->yanlis_dogru_orani);
        }else{
            $karne['net_sayisi'] = $dogru_sayisi;
        }

        $karne['sonucArr']      = $toplu_sonuc_dizisi_arr;
        $karne['adi_soyadi']    = $ogrencinin_cevap_kagidi[0]->adi_soyadi;
        $karne['sinav_adi']     = $this->sinavTbl[0]->sinav_adi;
        $karne['dogru_sayisi']  = $dogru_sayisi;
        $karne['yanlis_sayisi'] = $yanlis_sayisi;
        $karne['bos_sayisi']    = $bos_sayisi;
        $karne['user_id']       = $ogrenci_id;

        if(!$this->sinavTbl[0]->yanlis_dogru_orani){
            $karne['net_sayisi']    = $dogru_sayisi;
        }else{
            $karne['net_sayisi']    = $dogru_sayisi-($yanlis_sayisi/$this->sinavTbl[0]->yanlis_dogru_orani);
        }
        $karne['sonucArr']         = array_reverse($karne['sonucArr'],true);
        return $karne;
    }

    function soruBazliDegerlendirme(){
        $degerlendirme_Arr   = array();
        foreach ($this->soru_idleriArr as $soru_id) {
            $teksoru_degerlendirmesi =array();
            $cevap_kagitlari = array_reverse($this->cevap_kagitlari,true);
            foreach ($cevap_kagitlari as $kagit) {
                $user_id        = $kagit->user_id;
                $karne          = $this->ogrencininSonucKagidiniGetir($user_id);
                $toplu_sonuc_dizisi_arr = $karne['sonucArr'];
                $yanit          = $toplu_sonuc_dizisi_arr[$soru_id]['yanit'];
                if($yanit=='A'){
                    $teksoru_degerlendirmesi['A']     =$teksoru_degerlendirmesi['A']+1;
                }elseif($yanit=='B'){
                    $teksoru_degerlendirmesi['B']     =$teksoru_degerlendirmesi['B']+1;
                }elseif($yanit=='C'){
                    $teksoru_degerlendirmesi['C']     =$teksoru_degerlendirmesi['C']+1;
                }elseif($yanit=='D'){
                    $teksoru_degerlendirmesi['D']     =$teksoru_degerlendirmesi['D']+1;
                }elseif($yanit=='E'){
                    $teksoru_degerlendirmesi['E']     =$teksoru_degerlendirmesi['E']+1;
                }else{
                    $teksoru_degerlendirmesi['bos']   =$teksoru_degerlendirmesi['bos']+1;
                }

                $sonuc                     = $toplu_sonuc_dizisi_arr[$soru_id]['sonuc'];
                if($sonuc==1){
                    $teksoru_degerlendirmesi['dogru_sayisi']    =$teksoru_degerlendirmesi['dogru_sayisi']+1;
                }elseif($sonuc==0){
                    $teksoru_degerlendirmesi['yanlis_sayisi']   =$teksoru_degerlendirmesi['yanlis_sayisi']+1;
                }else{
                    $teksoru_degerlendirmesi['bos_sayisi']      =$teksoru_degerlendirmesi['bos_sayisi']+1;
                }
            }
                $teksoru_degerlendirmesi['basari_orani']=
                                        round(($teksoru_degerlendirmesi['dogru_sayisi']
                                        /$this->sinava_girmis_kullanici_sayisi)*100,2);
            $degerlendirme_Arr[$soru_id]=$teksoru_degerlendirmesi;
        }
        return $degerlendirme_Arr;
    }

    function ogrencininSirasiniGetir($user_id){
        $sayac  = 1;
        foreach ($this->sirali_sonuc_kagitlariArr as $kagit) {
            if($kagit['user_id']==$user_id){
                return $sayac;
            }
            $sayac++;
        }
    }































//    function netSayisiniHesaplaDbyeYaz(){
//        $query 		= " SELECT
//                            *
//                        FROM
//                            ".DB_PRFX."_sb_cevap_kagitlari
//                        WHERE
//                            sinav_no=".$this->sinav_no;
//
//        $kagitlar	= $this->db->get_results($query);
//
//        foreach($kagitlar as $kagit){
//            $query      = " SELECT
//                                    *
//                            FROM
//                                    ".DB_PRFX."_sb_cevap_kagitlari
//                            WHERE
//                                    sinav_no=".$this->sinav_no
//                            . ' AND user_id ='."'".$kagit->user_id."'";
//            $ogrencinin_cevap_kagidi        = $this->db->get_results($query);
//            //cevağ kağıdından öğrencinin cevaplarını alalım
//            $soru_id_yanit_ikili_dizisi     = explode('#',$ogrencinin_cevap_kagidi[0]->cevaplar);
//            $ogrenci_cevabi_arr= array();//soru_id key cavap value olacak
//            foreach ($soru_id_yanit_ikili_dizisi as $soru_id_yanit) {
//                $parcalarArr        = explode('_',$soru_id_yanit);
//                $ogrenci_cevabi_arr[$parcalarArr[0]]=$parcalarArr[1];
//            }
//
//            // doğru yanlış boş değerlendirmesi
//            // soru_id - dogrucevap - ogrencinin_yaniti - sonuc (boş ,yanlış ,doğru an biri)
//            $toplu_sonuc_dizisi_arr = array();
//            foreach ($this->sorularTbl as $soru) {
//                $soru_sonuc_dizisi_arr  = array();
//                $soru_id                = $soru->id;
//                $yanit                  = null;
//                $sonuc                  = '-';
//
//                // yanıt  boşmu , varsa doğrumu yanlışmı
//                if (isset($ogrenci_cevabi_arr[$soru_id])) {
//                    $yanit = strtoupper($ogrenci_cevabi_arr[$soru_id]);
//                    if ($yanit == $soru->cevap) {
//                        $sonuc = "1"; // yani doğru
//                    } else if($yanit=='') {
//                        $sonuc = ""; // boş
//                    }else{
//                        $sonuc = "0"; // yani yanlış
//                    }
//                }
//
//                //$soru_sonuc_dizisi_arr['soru_id']   = $soru_id;
//                $soru_sonuc_dizisi_arr['cevap']     = $soru->cevap;
//                $soru_sonuc_dizisi_arr['yanit']     = $yanit;
//                $soru_sonuc_dizisi_arr['sonuc']     = $sonuc;
//                $toplu_sonuc_dizisi_arr[$soru_id]   = $soru_sonuc_dizisi_arr;
//            }
//
//            //doğru yanlış sonuçlarını alalım
//            $dogru_sayisi   =0;
//            $yanlis_sayisi  =0;
//            $bos_sayisi     =0;
//            foreach ($toplu_sonuc_dizisi_arr as $sonuc){
//                if($sonuc['sonuc']=="1"){
//                    $dogru_sayisi++;
//                }elseif($sonuc['sonuc']=="0"){
//                    $yanlis_sayisi++;
//                }else{
//                    $bos_sayisi++;
//                }
//            }
//
//            if($this->sinavTbl[0]->yanlis_dogru_orani>0){
//                $karne['net_sayisi'] = $dogru_sayisi-($yanlis_sayisi/$this->sinavTbl[0]->yanlis_dogru_orani);
//            }else{
//                $karne['net_sayisi'] = $dogru_sayisi;
//            }
//
//
//            if(!$this->sinavTbl[0]->yanlis_dogru_orani){
//                $karne['net_sayisi']    = $dogru_sayisi;
//            }else{
//                $karne['net_sayisi']    = $dogru_sayisi-($yanlis_sayisi/$this->sinavTbl[0]->yanlis_dogru_orani);
//            }
//
//            $query      = " UPDATE
//                                ".DB_PRFX."_cevap_kagitlari
//                            SET
//                                net_sayisi=".$karne['net_sayisi']."
//                            WHERE
//                                user_id=".$kagit->user_id."
//                                AND sinav_no=".$this->sinav_no;
//            $this->db->get_results($query);
//        }
//    }
}