<?php
ini_set('display_errors',1);
error_reporting(E_ERROR  | E_PARSE);
session_start();

if (get_magic_quotes_gpc()) {
	function stripslashes_deep($value)
	{
		$value    = is_array($value) ?
		array_map('stripslashes_deep', $value) :
		stripslashes($value);
		return $value;
	}

	$_POST     = array_map('stripslashes_deep', $_POST);
	$_GET      = array_map('stripslashes_deep', $_GET);
	$_COOKIE   = array_map('stripslashes_deep', $_COOKIE);
	$_REQUEST  = array_map('stripslashes_deep', $_REQUEST);
}



include_once("../configuration.php");
include_once("../language/set.php");
include_once("../library/ezSQL/shared/ez_sql_core.php");
include_once("../library/ezSQL/pdo/ez_sql_pdo.php");
include_once("../library/Base.php");
include_once("../library/User.php");
include_once("../library/Pagination/Pagination.php");
include_once("../library/Filebrowser/FileBrowser.php");
include_once("../library/iBaseItem.php");
include_once("../library/iArticle.php");
include_once("../library/iCategory.php");
include_once("../library/iMediaCategory.php");
include_once("../library/iSection.php");
include_once("../library/iMediaSection.php");
include_once("../library/CHTML.php");
include_once("../library/Officer.php");
include_once("../library/BaseToolBar.php");
include_once("../library/BaseComponent.php");
include_once("../library/BaseView.php");
include_once("../library/BaseModel.php");
include_once("../library/BaseController.php");
include_once("../library/Core.php");


$CHTML      = new CHTML();
//db için
$db         = new ezSQL_pdo('mysql:host='.configuration::$db_host.';dbname='.configuration::$db_name,
                        configuration::$db_user,
                        configuration::$db_password);

$db->query("SET NAMES 'utf8'");
$db->query("SET CHARACTER SET utf8");
$db->query("SET COLLATION_CONNECTION = 'utf8_unicode_ci'");

$CORE 		        = new core($configuration);
$CORE->template 	="dinamik";
$CORE->instance();
if(!isset($_SESSION['user_id'])){
	$CORE->template='login';
}
$CORE->loadTemplate();