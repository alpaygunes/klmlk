<?php
define("DB_PRFX", "pr");
define("DS", "/");
define("GIRIS_KARTI", "1");
	if($_SERVER['HTTP_HOST']=="localhost"){
		class  configuration{
			static $site_url		=   'http://localhost/kelimelik';
			static $site_base_url	=   '/';
			static $db_user 		= 	'root';
			static $db_password		=	'1234';
			static $db_name			=	'mole';
			static $db_host			=	'localhost';
            static $base_image_dir			=	'albums';
			static $base_path		=	'/var/www';
            static $belgedizini		=	'belgeler/';
			static $cache			=	'cache';
            static $user_image_dir	=  	'images/users/';
			static $document_dir	=  	'document_dir/';
			static $makale_intro_karakter_sayisi	=  	500;
			static $makale_ilk_cumle		        =  	280;
			static $makale_intro_sayisi		        =	10;
		}
	}else {
		class  configuration{
			static $site_url		=   'http://www.alpaygunes.com/kelimelik';
			static $site_base_url	=   '/';
			static $db_user 		= 	'alpgun';
			static $db_password		=	'Erciyes38#';
			static $db_name			=	'alpgun_okulcan';
			static $db_host			=	'localhost';
			static $base_image_dir	=	'albums';
			static $base_path		=	'/var/www';
			static $belgedizini		=	'belgeler/';
			static $cache			=	'cache';
			static $user_image_dir	=  	'images/users/';
			static $document_dir	=  	'document_dir/';
			static $makale_intro_karakter_sayisi	=  	500;
			static $makale_ilk_cumle		        =  	280;
			static $makale_intro_sayisi		        =	10;
		}
	}
$configuration = new configuration();