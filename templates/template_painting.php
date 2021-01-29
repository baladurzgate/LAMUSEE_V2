<?php

$pa = $LMO; 


$picture = $lm->find_lmobject($pa->pictures[0]);

$map_name = $picture->LMID;

$linked_texts_id = $pa->linked_texts;

echo "<br>";

//printf("uniqid(): %s\r\n", uniqid());
echo uniqid();
echo "<br>";echo uniqid();
echo "<br>";echo uniqid();
echo "<br>";

$text = "";

if($linked_texts_id != null){

		if(gettype($linked_texts_id)=="array"){
			$text = $lm->find_lmobject($linked_texts_id[0]);
		}else{
			
			$text = $lm->find_lmobject($linked_texts_id);
		}
		
	echo $text->name;
	
}



?>

<map name="workmap">

</map>

<?php  ?>
	
	<figure id="illustration">
		<div class = "imgborder">
			<img id = "tableau" src="<?php echo $picture->file_path;?>"  border="0" usemap="#Map<?php $picture->LMID; ?>"/>

			<map name="#Map<?php $picture->LMID; ?>" >
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
				

			}

			?>
			</map>
			
		</div>
	</figure>
	
	<div class="col-left" >
		<div class="legende">...</div>
		<div id="history">...</div>
	</div>
	
		<div class="fils-right">
			<div class="slide-right"><a href="<?php  echo $lm->get_link($text);	 ?>">▲<br>texte</a></div>
		</div>

	

		<div class="fils-left">
			<div class="slide-left"><a href="<?php// echo $details_link; ?>">▲<br>detail</a></div>
		</div>

	