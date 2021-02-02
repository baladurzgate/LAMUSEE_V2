<!--REQUIRE THIS FILE IN A WP PAGE TEMPLATE !  -->
<?php require_once "LAMUSEE_V2/lamusee.php"; ?>
<?php 

echo "Class test";

$lm = new Lamusee();

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
					public  $wp_id; 
					public  $name;
					public  $nice_name;
					public  $lowres_image;  X
					public  $areas;        X
					public  $linked_text;
					public  $map_scale;    X
					public  $map_offset_x; X
					public  $map_offset_y;   X
					public  $image_highdef;  X
					public  $artiste;
					public  $titre_du_tableau;
					public  $technique;
					public  $date; -------------creation_date
					public  $dimensions;
					public  $lieu_de_conservation;
					public  $pays;
					public  $region;
					public  $artiste2;				
				
				
				*/
				
				// WP_ID

				$painting_params['wp_id'] = $post->ID;
				
				// NAME 
				
				$name_field = get_the_title($post->ID);
				$painting_params['name'] = $name_field;
				
				
				
				$preload = new painting('painting',$painting_params);
				$future_painting_LMID = $lm->generate_serial($preload);
				
				
				// NICE_NAME 
				
				$nice_name_field = get_the_title($post->ID);
				
				$painting_params['nice_name'] = $name_field;				
				
			
				
				//AREAS
				
				/*					
						public  $area_shape_name;
						public  $area_shape_type;
						public  $area_nice_name;
						public  $area_coords;
						public  $area_painting;
						public  $area_id;
						
				*/

				$lr_picture_params['name'] = get_field('lowres_image',$post->ID);
				$test_lr_picture = new picture($lr_picture_params);
				
				$future_picture_ID = $lm->generate_serial($test_lr_picture); // this LMID will be taken buy the second created picture

				$areas_field = get_field('areas',$post->ID);
				
				$areas_id_list = array();
				
				$shapes_id_list = array();
			
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
					$area_params['area_painting'] = $future_picture_ID;
					// for the moment area will have their own id system 
					$area_params['area_id'] = $area_params['area_shape_name'].$count;

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
					
					$found_shape ="";
			
					if($stored_shape == false){
						
						$new_shape = $lm->addObject('shape',$shape_params);
						
						$new_shape->add_painting($future_painting_LMID); //we link the wp_id to the shape
						
						$found_shape = $new_shape;
						
						
						
						
					}else{
						
						$stored_shape->add_painting($future_painting_LMID); //we link the wp_id to the shape
						
						$found_shape = $stored_shape;

						
					}
					
					array_push($shapes_id_list,$found_shape->LMID);
					
					
					
					$area_params['area_shape'] = $found_shape->LMID;
					
					//adding for the first time
					$new_area = $lm->addObject('area',$area_params);

					$count++;
					
					array_push($areas_id_list,$new_area->LMID);
				
				}
				
				echo'<br>';
				print_r($shapes_id_list);
				echo'<br>';
				
				
				$painting_params['linked_shapes'] = $shapes_id_list;
				
				// LOWRES_IMAGE -- the path to the image using the areas 
				
				// PICTURE
				
				/*
				
				
				$this->add_property("name","mediumtext");
				$this->add_property("wp_id","mediumtext");
				$this->add_property("file_path","mediumtext","file");
				$this->add_property("highres_image_path","mediumtext","file");
				$this->add_property("thumbnail_image_path","mediumtext","file");
				$this->add_property("areas","mediumtext","area",true);
				$this->add_property("map_scale","mediumtext");
				$this->add_property("map_offset_x","mediumtext");
				$this->add_property("map_offset_y","mediumtext");
				$this->add_property("dimensions","mediumtext");
				
				*/
				
				//HIGH REZ
		
				$highres_image_field = get_field('image_highdef',$post->ID);
				
				echo'<br>HIGHREZ';
				print_r($highres_image_field);
				echo'<br>';	
				
				$hr_picture_params = array();
				
				$hr_picture_params['name'] = $highres_image_field['filename'];
				$hr_picture_params['wp_id'] = $highres_image_field['ID'];
				$hr_picture_params['file_path'] = $highres_image_field['url'];
				$hr_picture_params['thumbnail_image_path'] = $highres_image_field['sizes']['thumbnail'];
				$hr_picture_params['width'] = $highres_image_field['width'];
				$hr_picture_params['height'] = $highres_image_field['height'];
				$hr_picture_params['size'] = $highres_image_field['filesize'];
				$hr_picture_params['areas'] = array();
				$hr_picture_params['map_scale'] = 0;
				$hr_picture_params['map_offset_x'] = 0;
				$hr_picture_params['map_offset_y'] = 0;
				$hr_picture_params['dimensions'] = 0;


				/*$test_hr_picture = new picture($hr_picture_params);
				
				$stored_hr_picture = $lm->alreadyExist($test_hr_picture);
				
				$hr_found_id = "";
				
				if($stored_hr_picture==true){
					
					$hr_found_id = $stored_hr_picture->LMID;
					
					
				}else{
					*/
					$new_hr_picture = $lm->addObject("picture",$hr_picture_params);
					$hr_found_id = $new_hr_picture->LMID;
					
				//}
				
				echo'<br> hr_found_id ******************************';
				print_r($hr_found_id);
				echo'<br>';				
				
				//LOW RES (parent of the high res) 
				
				$lowres_image_field = get_field('lowres_image',$post->ID);
				
				echo'<br>';
				print_r($lowres_image_field);
				echo'<br>';
				
				$picture_params = array();
				
				$picture_params['name'] = $lowres_image_field['filename'];
				$picture_params['wp_id'] = $lowres_image_field['ID'];
				$picture_params['file_path'] = $lowres_image_field['url'];
				$picture_params['thumbnail_image_path'] = $lowres_image_field['sizes']['thumbnail'];
				$picture_params['width'] = $lowres_image_field['width'];
				$picture_params['height'] = $lowres_image_field['height'];
				$picture_params['size'] = $lowres_image_field['filesize'];
				$picture_params['areas'] = $areas_id_list;
				$picture_params['map_scale'] = get_field('map_scale',$post->ID);
				$picture_params['map_offset_x'] = get_field('map_offset_x',$post->ID);
				$picture_params['map_offset_y'] = get_field('map_offset_y',$post->ID);
				$picture_params['dimensions'] = get_field('dimensions',$post->ID);
				$picture_params['highres_image'] = $hr_found_id; // connexion with the high res image
				
				$test_picture = new picture($picture_params);
				
				$stored_picture = $lm->alreadyExist($test_picture);
				
				$picture_found_id = "";
				
				if($stored_picture==true){
					

					$picture_found_id= $stored_picture->LMID;
	
				}else{
					
					$new_picture = $lm->addObject("picture",$picture_params,$future_picture_ID);
					$picture_found_id = $new_picture->LMID;
					
				}
				
				$pictures_array = array();
							
				array_push($pictures_array,$picture_found_id);
				
				$painting_params['pictures'] = $pictures_array;

				// ARTISTE

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
				
				//PERIOD : 
				
				$period_params['name'] = 'vie de '.$artist_name;
				$period_params['first_date'] = $artist_dates[0];
				$period_params['last_date'] =  $artist_dates[1];
				
				// often a new period
				$period_id = $lm->add_or_call_object('period',$period_params);
				
				$artist_params = array();
				
				$artist_params['name'] = $artist_name;
				$artist_params['period'] = $period_id;
				$artist_params['place_of_birth'] = "";
				$artist_params['country'] = "";
				$artist_params['profession'] = "artiste";
				$artist_params['work_place'] = "";
				$artist_params['biography'] = "";
				
				$test_artist = new people($artist_params);
				
				$stored_artist = $lm->alreadyExist($test_artist);
				
				if($stored_artist == false){
					
					$new_artist = $lm->addObject('people',$artist_params);
					
					$painting_params['artiste'] = $new_artist->LMID;
					
				}else{
					
					$painting_params['artiste'] = $stored_artist->LMID;
					
				}
				
				//TITRE DU TABLEAU 
				
				
				//DATE
				
				$date_field = get_field('date',$post->ID);
				$dates =  extract_date($date_field);
				$painting_params['dates'] =$dates[0];
				
				
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
				
				$region_params['name'] = $region_field;
				$region_params['type'] = "region";		
				$region_params['description'] = "";		
				
				$test_region = new region($region_params);
				
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
				$pays_params['description'] = "";		
				
				$test_pays = new region($pays_params);
				
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
				
				// DATE 
				
				$painting_params['creation_date'] = extract_date(get_field('date',$post->ID))[0];


				
				
				// ARTISTE 2  
								
				$artiste2_field = get_field('artiste2',$post->ID);
				
				//LINKED_TEXT(s) 
				/*
				
					public  $wp_id; 
					public  $name;
					public  $nice_name;
					public  $content;
					public  $author;
					public  $translator;
					public  $date;
					public  $linked_book;
					
				
				*/
				
				

				$linked_texts_field = get_field('linked_text',$post->ID);
				
				$linked_texts = array();
				
				if(gettype($linked_texts_field)=="array"){
				
					foreach($linked_texts_field as $wp_text){
						
						$text_post_id = $wp_text->ID;
						
						$text_params = array();
						$text_params['wp_id'] = $wp_text->ID;				
						$text_params['name'] = $wp_text->post_name;				
						//$text_params['content'] = addslashes($wp_text->post_content);		
						$text_params['content'] = addslashes($wp_text->post_content);		

						//AUTHOR


						$author_params = array();
						$author_params['name'] = get_field('author',$text_post_id );	
						//AUTHOR PERIOD : 
						
						$aut_date = extract_date($author_params['name']);
						
						$period_params['name'] = 'vie de '.$author_params['name'];
						$period_params['first_date'] = $aut_date[0];
						$period_params['last_date'] =  $aut_date[1];
						$period_id = $lm->add_or_call_object('period',$period_params);	
						
						$author_params['period'] = $period_id;
						$author_params['profession'] = 'ecrivain';
						
						$text_params['author'] =$lm->add_or_call_object('people',$author_params);

						
						//TRANSLATOR
						
				

						$translator_params = array();
						$translator_params['name'] = get_field('traductor',$text_post_id );	
						
						//TRAS PERIOD : 
						
						$trad_date = extract_date($translator_params['name']);
						
						$period_params['name'] = 'vie de '.$translator_params['name'];
						$period_params['first_date'] = $trad_date[0];
						$period_params['last_date'] =  $trad_date[1];
						$period_id = $lm->add_or_call_object('period',$period_params);	
						
						$translator_params['period'] = $period_id;
						$translator_params['profession'] = 'translator';
						
						
						
						
						$text_params['translator'] = $lm->add_or_call_object('people',$translator_params);

						
						// often a new period
						
						

						$text_params['publishing_date'] = extract_date(get_field('publishing_date',$text_post_id ))[0];	


						//LINKED_BOOK
						
						$book_params = array();
						$book_params['title'] = get_field('book_title',$text_post_id );
						$book_params['author'] = $text_params['author'];
						$called_book = $lm->add_or_call_object('book',$book_params,true);	
						$text_params['linked_book'] =$called_book->LMID;	
						
						$called_text = $lm->add_or_call_object('text',$text_params,true);	
						
						$called_book->link_text($called_text->LMID);
						
						array_push($linked_texts,$called_text->LMID);
						
					}
				
				}

				$painting_params['linked_texts'] =$linked_texts;			

				
				// NEW PAINTING ! 
				
				//relinking painting to shapes
				
				
				$lm->addObject('painting',$painting_params,$future_painting_LMID);
				
				
				
			}
			
}

 
$lm-> update_tables();

$lm-> display_tables();

echo $lm->get_log_html();
$lm->save_log();



?>


