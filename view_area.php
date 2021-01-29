<?php
require_once "lamuseeV2_main.php"; 

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