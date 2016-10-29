<?php

/**
 * Class Core
 * Bu sınıf frameworkun temel metotlarını içerir
 */
class Core {
    var $redirectUrl	= array();
    var $template		= "fenokulu";
    var $component;
    var $baslik;
    var $controller;
    var $model;
    var $view;
    var $ASSIGNED		= array();
    var $request		= array();
    var $component_output;
    var $site_url;
    var $messages 		= array();
    var $modules 		= array();
    var $pozitions		= array('top'=>'null','left'=>'null','center'=>'null','rigth'=>'null','bottom'=>'null');
    var $base_path		='';
    var $pagination;
    var $username;
    var $rol;
    var $adi_soyadi;
    var $user_id;
	var $profil;
    var $userProfil;

    function  __construct(){
	    //global $db;
        $this->template 	= "esnek";
        $this->component	= "frontpage";
        $this->assign		= "";
        $this->baslik		= _HOS_GELDINIZ_;
        $this->user_id      = $_SESSION['user_id'];
        $this->username     = $_SESSION['username'];
        $this->adi_soyadi   = $_SESSION['adi_soyadi'];
        $this->rol          = $_SESSION['rol'];
	    $profil             = $this->loadHelper('Profil');
        $this->userProfil   = $profil;
	    $this->profil       = $profil->instance($this);
        $_SESSION['okulcan_profil'] = $this->profil[0];
    }

    function instance(){
        if(isset($_SESSION['error'])){
            $this->messages[]	=	$_SESSION['error'];
            unset($_SESSION['error']);
        }
        //istekleri parse et
        $this->request();
        //posizyonları ayarla
        $this->setPositions();

        //modul gösterim ve konum ayarları
        $this->configureModules();
        //istenen componentleri include et
        $this->loadModules();
        $this->loadCompanent($this->component);
    }

    //------------------------POST ve GET leri diziye al
    function request(){
        foreach ($_REQUEST as $anahtar=>$istek){
            $this->request[$anahtar]				= $istek;
        }
        if(isset($this->request['component'])){
            $this->component 						= $this->request['component'];
        }
    }

    function loadCompanent($component=null){
        include 'components/'.$component.'/'.$component.'.php';
        $ComponentPage 								= new $component();
        $ComponentPage->portal->component_output	= $ComponentPage->view->getOutput(null);
    }

    function loadTemplate($template = null){
        if(!isset($this->request['no_template'])){
            if($template){
                $this->template 						= $template;
            }
            include 'templates/'.$this->template.'/'.$this->template.'.php';
        }else{
            echo $this->component_output;
        }
    }

    function showMessages(){
        $output 									=    null;
        if(count($this->messages)){
            foreach ($this->messages as $messages){
                $output 							.=   $messages['text']."\n";
            }
// 			$output 								= "<div class=\"mesagges\">
// 														<div id=\"".$messages['type']."\">$output</div>
// 													   </div>";
            if($messages['type']=="warning"){
                $messages['type'] = "uyari-kutusu alert-warning";
                $output =  "<strong>  Uyarı </strong> ".$output;
            }else if($messages['type']=="info"){
                $messages['type'] = "uyari-kutusu alert-info";
                $output =  "<strong>  Bilgi </strong> " . $output;
            }else if($messages['type']=="error"){
                $messages['type'] = "uyari-kutusu alert-danger alert-error";
                $output =  "<strong>  Hata </strong> " . $output;
            }

            $output = 	"
	            <div class=\"alert ". $messages['type']."\"  style=\"line-height:25px;\">".
	                "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>".
	                " $output ".
                "</div>
             ";
            unset($this->messages);
        }
        return $output;
    }

    //gösterilmesi istenen moduller şablondan önce yüklenip değişkene alınmalı
    function loadModules(){

        $this->loadedModules['login']               =	$this->loadModul('login',array(
            //'sectionID'=>6,
            //'categoryID'=>15,
            //'articleID'=>6
            //'header'=>'ÜYE GİRİŞİ'
        ));

        $this->loadedModules['anamenu']             =	$this->loadModul('mod_anamenu',array(
            //'sectionID'=>6,
            //'categoryID'=>15,
            //'articleID'=>6
            //'header'=>'ÜYE GİRİŞİ'
        ));

	    $this->loadedModules['ust_anamenu']       =	$this->loadModul('mod_ust_anamenu',array(
		    //'sectionID'=>6,
		    //'categoryID'=>15,
		    //'articleID'=>6
		    //'header'=>'ÜYE GİRİŞİ'
	    ));

        $this->loadedModules['mod_dokumanlar']   =	$this->loadModul('mod_dokumanlar',array(
            //'sectionID'=>6,
            //'categoryID'=>15,
            //'articleID'=>6,
            'header'=>'SON EKLENEN DÖKÜMANLAR'
        ));

        //bu modulden iki tane olunca angulajjs sorun çıkartıyor
        // controller adı farklı olmalı modül dizinini kopyala yarak çoağaltmak ve xontroller daını değiştirmek lazım
/*	    $this->loadedModules['populer_ilkogretim']   =	$this->loadModul('mod_dokuman_kategori_liste',array(
		    //'sectionID'=>6,
		    'kategoriId'=>8,
		    //'articleID'=>6,
		    'header'=>'Popüler İlköğretim Dökümanları'
	    ));*/

/*	    $this->loadedModules['populer_ortaogretim']   =	$this->loadModul('mod_dokuman_kategori_liste',array(
		    //'sectionID'=>6,
		    'kategoriId'=>9,
		    //'articleID'=>6,
		    'header'=>'Popüler Ortaöğretim Dökümanları'
	    ));*/


        $this->loadedModules['sonMakaleler']   =	$this->loadModul('mod_makaleler',array(
            //'sectionID'=>6,
            //'kategoriId'=>13,
            //'articleID'=>6,
            'header'=>'HABERLER'
        ));
        $this->loadedModules['mod_carousel']          =	$this->loadModul('mod_carousel',array());

        $this->loadedModules['mod_blog_ag_linkleri']  = $this->loadModul('mod_blog_ag_linkleri',array());

        $this->loadedModules['mod_sosyal_ag_iconlari']= $this->loadModul('mod_sosyal_ag_iconlari',array());

        $this->loadedModules['mod_sagmodul1']= $this->loadModul('mod_sagmodul1',array());


        //-----------------öğretmen ağı için moduller burdan sonra----------//
        $this->loadedModules['mod_ogretmen_agi_anamenu']             =	$this->loadModul('mod_ogretmen_agi_anamenu',array());
        $this->loadedModules['mod_ogretmen_agi_paylasim']            =	$this->loadModul('mod_ogretmen_agi_paylasim',array());
        $this->loadedModules['mod_ogretmen_agi_hesap_yonetimi']      =	$this->loadModul('mod_ogretmen_agi_hesap_yonetimi',array());
        $this->loadedModules['mod_ogretmen_agi_profil_resmi']        =	$this->loadModul('mod_ogretmen_agi_profil_resmi',array());

    }

    function loadModul($modul_name,$params=NULL){
        //eğer component isteyi varsa
        ob_start();

        if(array_key_exists('component', $this->request)){
            //without_this göre modulleri yükle . componentlerle beraber gösterilmek istenmeye bilir
            $component 			= $this->request['component'];
            $without_this		= $this->modules[$modul_name]['without_this'];
            $without_this		= explode(',',$without_this);
            if(!in_array($component, $without_this)){
                include "modules/$modul_name/$modul_name.php";
            }
        }else{
            include "modules/$modul_name/$modul_name.php";
        }
        $output				= ob_get_contents();
        ob_end_clean();
        return $output;
    }

    function configureModules(){
        $setting = array();
        //login modul setings
        $setting['position'] 			= 'left'; //şimdilik gereksiz . çünkü themeden ayarlanıyor
        $setting['order'] 				= '1';
        //$setting['without_this'] 		= 'membership';// virgülle ayrılaca
        $this->modules['login']			=  $setting;
    }

	function setPositions(){
        //$this->pozitions['left']		= 'hide';
        //$this->pozitions['right']		= 'hide';
    }

    function redirectUrl($url){
        header('Location: '.$url);
    }

    function loadHelper($helpername,$path=null){
        $dosya  =  '../helpers/'.$helpername.'.php';
        if(file_exists($dosya)){
            include $dosya;
        }else{
            include 'helpers/'.$helpername.'.php';
        }

        return   new $helpername();
    }

    function cerateScriptLink($file,$for=null){
	    //boş ise modul için
	    if($for){
		    $link = "<script type=\"text/javascript\" ";
		    $link.= " src=\"modules/".$for."/$file\" ";
		    $link.= "></script>";
	    }else{// değilse bileşen için
		    $link = "<script type=\"text/javascript\" ";
		    $link.= " src=\"components/".$this->request['component']."/assets/$file\" ";
		    $link.= "></script>";
	    }

        return $link;
    }
}