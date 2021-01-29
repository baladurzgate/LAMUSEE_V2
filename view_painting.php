<?php require_once "templates/head.php"?>


<?php
require_once "lamusee.php"; 

$lm = new Lamusee();

$lm->load_tables();




?>


<?php

foreach($lm->paintings as $pa){

	
	
	
}

//include('templates/template_'.$current_class.'.php');


?>


<?php require_once "templates/tail.php"?>