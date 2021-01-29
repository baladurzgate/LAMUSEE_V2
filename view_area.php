<?php
require_once "lamusee.php"; 

$lm = new Lamusee();

$lm->load_tables();


?>


<?php

foreach($lm->areas as $a){

	
	if($a->getPng()==false){
		
		$a->createPng($lm);
		
	}
	
	echo $a->getPng();
}

//include('templates/template_'.$current_class.'.php');


?>