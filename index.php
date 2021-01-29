<?php
require_once "lamuseeV2_main.php"; 
$lm = new Lamusee();

$lm->load_tables();

foreach($lm->paintings as $painting){
	
	echo '<br>';
	echo  $lm->get_html_link($painting);
	
	echo '<br>';
	foreach($painting->pictures as $pid){
		$pict = $lm->find_lmobject($pid);
		echo '<br>';
		$pict->name;
		echo  $pict->get_thumbnail_html();
		echo '<br>';
		echo $lm->get_html_link($pict);
	}
	
	echo '<br>';
	
}

?>