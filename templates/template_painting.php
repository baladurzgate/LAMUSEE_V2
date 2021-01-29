<?php

$pa = $LMO; 


$picture = $lm->find_lmobject($pa->pictures[0]);


?>

<img src="<?php echo $picture->file_path;?>" alt="Workplace" usemap="#workmap">

<map name="workmap">
<?php

foreach($picture->areas as $area_id){
	
	$a = $lm->find_lmobject($area_id);
	
	$shape = $lm->find_lmobject($a->area_shape);
	
	$random_painting_id = $shape->choose_random_painting($pa->LMID);

	if($random_painting_id!=false){
		
		$linked_painting = $lm->find_lmobject($random_painting_id);
		
		$link = $lm->get_link($linked_painting);	
		
		echo $a->areaToHTML($link);		
	}
	
	//echo $a->areaToHTML($link);	

}

?>
</map>