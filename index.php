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

if(isset($_GET["id"])){
	
	$_ID = $_GET["id"]; 	

}
if(isset($_GET["page"])){
	
	$_PAGE = $_GET["page"]; 	

}

if($_ID!=false&&$_PAGE==false){
	
	include ("view_object.php") ;
	
}

if($_PAGE!=false){
	
	include ("view_".$_PAGE.".php") ;
	
}

if($_ID==false&&$_PAGE==false){
	
	include "web/home.php"; 
	
}



?>


<?php require_once "web/footer.php"?>