<?php
if(!isset($_SESSION['language'])){
	include 'TR_tr.php';
}else{
	include $_SESSION['language'].'.php';
}