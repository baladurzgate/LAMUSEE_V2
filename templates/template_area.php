<?php


/*foreach($LMO->properties as $p){

	$p->get_html_output($lm,$LMO);
	
}*/

	if($LMO->getPng()==false){
		
		$LMO->createPng($lm);
		
	}
	
	echo $LMO->getPng();

?>
