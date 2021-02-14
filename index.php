<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "LAMUSEE_V2";

include "lamusee.php"; 

$lm = new Lamusee();

$lm->load_tables();?>

<?php include "web/header.php"?>

<?php 

$_ID = false;

$_PAGE = false; 

$_MODE = false;

if(isset($_GET["id"])){
	
	$_ID = $_GET["id"]; 	

}
if(isset($_GET["page"])){
	
	$_PAGE = $_GET["page"]; 	

}

if(isset($_GET["mode"])){
	
	$_MODE = $_GET["mode"];	

}


if($_ID!=false&&$_MODE=='view'){
	
	include ("view_object.php") ;
	
}


if($_MODE=='edit' &&$_ID!=false){
	
	include ("edit_object.php") ;
	
}

if($_ID==false&&$_PAGE==false){
	
	include "admin.php"; 
	//include "web/home.php"; 
	
}



?>


<?php require_once "web/footer.php"?>