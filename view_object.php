

<?php

	$current_ID = $_ID;

	$LMO = $lm->find_lmobject($current_ID);

	$current_class = get_class($LMO);	
	
      
?>

<h1><?php echo $LMO->getKeyPropertyValue()  ?></h1>

<?php

include('templates/template_'.$current_class.'.php');

?>

