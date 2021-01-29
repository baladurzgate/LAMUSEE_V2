<?php
require_once "lamuseeV2_main.php"; 

$lm = new Lamusee();

$lm->load_tables();

$current_ID = $_GET["id"]; 

$LMO = $lm->find_lmobject($current_ID);

$current_class = get_class($LMO);

?>

<h1><?php echo $LMO->getKeyPropertyValue()  ?></h1>

<?php

foreach($LMO->properties as $p){

	$p->get_html_output($lm,$LMO);
}

include('templates/template_'.$current_class.'.php');


?>