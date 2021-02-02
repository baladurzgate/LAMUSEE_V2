<?php
require_once "lamusee.php"; 
$lm = new Lamusee();

$lm->load_tables();?>

<?php require_once "web/header.php"?>

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