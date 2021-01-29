<?php
require_once "lamusee.php"; 

$lm = new Lamusee();

$lm->load_tables();


?>


<?php

foreach($lm->areas as $a){

	//$a->createPng($lm);
	
	$a->getPng();
}

//include('templates/template_'.$current_class.'.php');


?>