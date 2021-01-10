<?php /* Template Name: SYNC*/ get_header(); ?>

<?php require_once "classes/LAMUSEE_Class.php"; ?>
<?php 

echo "Class test";


$lm; 
$lm = new Lamusee();

//$lm->create_tables();

$shape_list = get_shape_list();

$people_list = ""; 


function compareByName($a, $b) {
	return strcmp($a["name"], $b["name"]);
}
//usort($shape_list, 'compareByName');

$a = 0;

// would be nice to have them by creation dates 

$query = array( 'post_status' => 'publish','numberposts' => 20);

$all_published_posts = get_posts($query);



 $lm->create_tables();
 $lm->load_tables();

	
?>

	
<?php	

// BIG LOOP TO EXTRACT ALL CLASS INTANCES FROM WP 

// FIRST LEVEL THROUGH PAINTINGS ; 

foreach ( $all_published_posts as $post ) {
	
	
			if(get_post_format( $post->ID )== 'image'){
				
				echo "IMAGE";
				
				// we are gradualy going to fill this param array in order to create the new LMOBJECT class Painting 
				
				$painting_params = array();
				
				
				// PAINTING ;
				
				/*
					public  $id; 
					public  $name;
					public  $nice_name;
					public  $lowres_image;
					public  $areas;
					public  $linked_text;
					public  $map_scale;
					public  $map_offset_x;
					public  $map_offset_y;
					public  $image_highdef;
					public  $artiste;
					public  $titre_du_tableau;
					public  $technique;
					public  $date;
					public  $dimensions;
					public  $lieu_de_conservation;
					public  $pays;
					public  $region;
					public  $artiste2;				
				
				
				*/
				
				// NAME 
				
				$name_field = get_the_title($post->ID);
				
				
				
				$painting_params['name'] = $name_field;
				
				
				
				// NICE_NAME 
				
				$nice_name_field = get_the_title($post->ID);
				
				$painting_params['nice_name'] = $name_field;				
				
				
				// LOWRES_IMAGE -- the path to the image using the areas 
				
				$lowres_image_field = get_field('lowres_image',$post->ID);
				$painting_params['lowres_image'] = $lowres_image_field;

				
				//AREAS
				
				/*					
						public  $area_shape_name;
						public  $area_shape_type;
						public  $area_nice_name;
						public  $area_coords;
						public  $area_painting;
						public  $area_id;
						
				*/

				$areas_field = get_field('areas',$post->ID);
				
				$areas_id_list = array();
			
				$count = 0;
				
				
				$doc = new DOMDocument();
				$doc->loadHTML($areas_field);
				
				$area_tags = $doc->getElementsByTagName( "area" );
				
				foreach( $area_tags  as $area_tags  ){
					
					$area_params = array();
					
					//to do : there should be a link to the image using the area in the area_params

					/*
						fitting this : 
						
						<area 
						shape="poly" 
						coords="330,280,394,106,401,109,390,136,340,273" 
						href="#baguette" 
						alt="baguette" 
						title="baguette" 
						tyle="cursor: initial;"
						>
						
						into this : 
					
	
					
					*/
					
					$area_params['area_shape_name'] = $area_tags->getAttribute('alt');
					$area_params['area_shape_type'] = $area_tags->getAttribute('shape');
					$area_params['area_nice_name'] = $area_tags->getAttribute('title');
					$area_params['area_coords'] =  $area_tags->getAttribute('coords');
					//painting linked : this one
					$area_params['area_painting'] =  $post->ID;
					// for the moment area will have their own id system 
					$area_params['area_id'] =  $post->ID.$area_params['area_shape_name'].$count;

					array_push($areas_id_list,$area_params['area_id']);
					
					
					//adding for the first time
					$lm->addObject('area',$area_params);
					
					$count++;
					
					
					// SHAPE 
					
						/*public $shape_ID;
						public $shape_name;
						public $shape_creation_date;
						public $shape_last_modification;
						public $shape_nice_name;
						public $shape_paintings_list;
						public $shape_clicks;
						*/		
					
					
					$shape_params = array();
					
					$shape_params["shape_name"] = $area_tags->getAttribute('alt');
					$shape_params["shape_nice_name"] = $area_tags->getAttribute('title');
					$shape_params["shape_ID"] = $area_tags->getAttribute('alt').$post->ID;
					
					$test_shape = new shape($shape_params);
					
					$stored_shape = $lm->alreadyExist($test_shape);
					
					if($stored_shape == false){
						
						$lm->addObject('shape',$shape_params);
						
					}else{
						
						$stored_shape->add_painting($post->ID);
						
					}
					
					
					
					
				}
				
				$painting_params['areas'] = array_to_string_coma_list($areas_id_list);

				// ARTISTS

				/*
				
					class people
				
					public $name;
					public $period;
					public $place_of_birth;
					public $country;
					public $profession;
					public $work_place;
					public $biography;

				
				*/				
				
				$artist_field = get_field('artiste',$post->ID);
				$artist_name = extract_artist_name($artist_field);
				
				$artist_dates = extract_date($artist_field);
				
				$artist_params = array();
				
				$artist_params['name'] = $artist_name;
				$artist_params['period'] = array_to_string_coma_list($artist_dates);
				$artist_params['place_of_birth'] = "";
				$artist_params['country'] = "";
				$artist_params['profession'] = "";
				$artist_params['work_place'] = "";
				$artist_params['biography'] = "";
				
				$test_artist = new people($artist_params);
				
				$stored_artist = $lm->alreadyExist($test_artist);
				
				if($stored_artist == false){
					
					$new_artist = $lm->addObject('people',$artist_params);
					
					$painting_params['areas'] = $new_artist->LMID;
					
				}else{
					
					$painting_params['areas'] = $stored_artist->LMID;
					
				}
				
				//TITRE DU TABLEAU 
				
				
				//DATE
				
				$date_field = get_field('date',$post->ID);
				$dates =  extract_date($date_field);
				$painting_params['dates'] =array_to_string_coma_list($dates);
				
				
				// LIEU DE CONSERVATION 
				
				/*
					class place 
					
					public $name;
					public $description;
					public $coords;
					
				*/
				
				$ldc_field = get_field('lieu_de_conservation',$post->ID);
				
				$place_params = array();
				$place_params['name'] = $ldc_field;
				$place_params['description'] = "TBD";
				
				$test_place = new place($place_params);
				
				$stored_place = $lm->alreadyExist($test_place);
				
				if($stored_place == false){
					
					$new_place = $lm->addObject('place',$place_params);
					
					$painting_params['lieu_de_conservation'] =$new_place->LMID;
					
				}else{
					
					$painting_params['lieu_de_conservation'] =$stored_place->LMID;
					
				}				
				
				
				$painting_params['lieu_de_conservation'] ="";
				
				
				
				// REGION 
				
				/*
				
					class region 
					public $name;
					public $type;
					public $description;
					public $coords;
					public $points;
					public $linked_places; 
					public $linked_regions; 				
					
				
				*/
				
				$region_field = get_field('region',$post->ID);
				
				$region_params['name'] = $pays_field;
				$region_params['type'] = "region";		
				$region_params['description'] = "TBD";		
				
				$test_region = new place($region_params);
				
				$stored_region = $lm->alreadyExist($test_region);
				
				if($stored_region == false){
					
					$new_region = $lm->addObject('region',$region_params);
					
					$painting_params['region'] =$new_region->LMID;
					
				}else{
					
					$painting_params['region'] =$stored_region->LMID;
					
					
					
				}	
				
				// PAYS 
				
				/*
				
					class region 
					public $name;
					public $type;
					public $description;
					public $coords;
					public $points;
					public $linked_places; 
					public $linked_regions; 				
					
				
				*/
				
				$pays_field = get_field('pays',$post->ID);
				
				$pays_params['name'] = $pays_field;
				$pays_params['type'] = "pays";		
				$pays_params['description'] = "TBD";		
				
				$test_pays = new place($pays_params);
				
				$stored_pays = $lm->alreadyExist($test_pays);
				
				// we link the region to the country 
				
				if($stored_pays == false){
					
					$new_pays = $lm->addObject('region',$pays_params);
					
					$painting_params['pays'] =$new_pays->LMID;
					
					$new_pays->link_region($painting_params['region']);
					
				}else{
					
					$painting_params['pays'] =$stored_pays->LMID;
					
					$stored_pays->link_region($painting_params['region']);
					
				}				
				
				
				// ARTISTE 2  
								
				$artiste2_field = get_field('artiste2',$post->ID);
				
				
				// NEW PAINTING ! 

				
				$lm->addObject('painting',$painting_params);
				
				
				
			}
			
}

 
$lm-> update_tables();

 
$lm-> display_tables();

// SECOND LEVEL THROUGH OBJECTS COMMON TO SEVERAL PAINTINGS (SHAPES, AUTHOR, ect...)


//print_r($lm->places);
//print_r($lm->shapes);
//print_r($lm->paintings);


?>



