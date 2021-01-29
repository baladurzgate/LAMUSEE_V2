<?php
class area extends LMObject{
	
	public  $area_shape_name;
	public  $area_shape_type;
	public  $area_shape; // to link the area to a shape with LMID 
	public  $area_nice_name;
	public  $area_coords;
	public  $area_painting;
	public  $area_id;

	
	public function __construct($param) { 
	
		parent::__construct();

		$this->LMClass = "area";
		
		//attention a ne pas mettre d'espaces dans les string!! cela donne une erreur SQL "
		
		$this->add_property("area_shape_name","mediumtext");
		$this->add_property("area_shape_type","mediumtext");
		$this->add_property("area_shape","mediumtext","shape");
		$this->add_property("area_nice_name","mediumtext");
		$this->add_property("area_coords","mediumtext");
		$this->add_property("area_painting","mediumtext","picture");
		$this->add_property("area_id","mediumtext");
		
		$this->setKeyProperty("area_id");
			
		if(gettype ( $param )== "array"){
			
			foreach($param as $key => $row){
				
				$class = get_class($this);
			
				if(property_exists ($class,$key)) {
				
					if($this->properties[$key]->isArray == true){
						
						if(gettype($row)=="array"){
							
							$this->$key = $row;
							
						}else{
							
							$decoded_array = json_decode($row);
						
							$this->$key = $decoded_array;	
							
						}
						

						
					}else{
						
						$this->$key = $row;
						
					}
					
					
			
				}
			
			}			
			
		}		

	
	}
	
	
	public function getShapeName(){ return $this->area_shape_name;}
	public function setShapeName($sn){ $this->area_shape_name = $sn;}
		
	public function getShapeType(){ return $this->area_shape_type;}
	public function setShapeType($st){ $this->area_shape_type = $st;}
	
	public function getCoords(){ return $this->area_coords;}
	public function setCoords($c){ $this->area_coords = $c;}
	
	public function getNiceName(){ return $this->area_nice_name;}
	public function setNiceName($nn){ $this->area_nice_name = $nn;}
	
	
	public function getPainting(){ return $this->area_painting;}
	public function setPainting($p){ $this->area_painting = $p;}
	
	
	public function getID(){ return $this->area_id;}
	public function setID($i){ $this->area_id = $i;}
	
	public function areaToHTML($link){
		
		//$link = $_link != undefined && $_link != null ? $_link : '#'.$this->area_shape_name;
	
		$HTML	=  '<area shape="'.$this->area_shape_type.'" coords="'.$this->area_coords.'" href="'.$link.'" alt="'.$this->area_shape_name.'" title = "'.$this->area_nice_name.'"  areaID ="'.$this->area_id.'">'."\n";
		return $HTML;
		
	}
	
	public function getPointsArray(){
		return explode(',',$this->area_coords);
	}
	
	public function areaTOPng(){
		
		$points = $this->getPointsArray();
		$image = imagecreatetruecolor(400, 300);
		$col_poly = imagecolorallocate($image, 255, 255, 255);
		
		// Draw the polygon
		imagepolygon($image, $points , count($points)/2,$col_poly);

		// Output the picture to the browser
		$path = 'data/png/test.png';
		
		echo imagepng($image,$path);
		
		echo '<img src="'.$path .'">';
		
		
	}
	
	
	
	public function getBoundingBox(){
		
		$box = Array();
		
		$points = $this->getPointsArray();

		$coords_x = Array();
		$coords_y = Array();
		
		$max_x = 0;
		$max_y = 0;
		
		
		for($i = 0 ; $i < count($points);$i++){

			$py =0;
			$px =0;
			
			if($i % 2 == 0){
				array_push($coords_x,$points[$i]);
			}else{
				array_push($coords_y, $points[$i]);		
			}		

		}
		
		$max_x = max($coords_x);
		$min_x = min($coords_x);
		
		$max_y = max($coords_y);
		$min_y = min($coords_y);
		
		$w = $max_x-$min_x;
		$h = $max_y-$min_y;
		$x = $min_x;
		$y = $min_y;
		
		$box['x'] = $x;
		$box['y'] = $y;
		$box['width'] = $w;
		$box['height'] = $h;

		return $box;
		
	}
	
	public function createMask($lm){
		
		$linked_picture = $lm->find_lmobject($this->area_painting);
		$image_path = $linked_picture->file_path;
	
		
		$ref_image = imagecreatefromjpeg($image_path);	
		$wSize = imagesx($ref_image);
		$hSize = imagesy($ref_image);
		
		$mask = imagecreatetruecolor ($wSize ,$hSize );
		$black = imagecolorallocate($mask , 0, 0, 0);
		imagefill($mask, 0, 0, $black );		
		
		$white = imagecolorallocate($mask , 255, 255, 255);
		$points = $this->getPointsArray();
		imagefilledpolygon($mask, $points , count($points)/2,$white);	
		
		return $mask;
		
	}
	
	public function getPng(){
		
		$path = 'data/png/'.$this->area_id.'.png';
		echo '<img src="'.$path .'">';
	}
	

	
	public function createPng($lm){
		
		$linked_picture = $lm->find_lmobject($this->area_painting);
		$image_path = $linked_picture->file_path;
		
		$image = imagecreatefromjpeg($image_path);
		
		$mask = $this->createMask($lm);
		
		$this->imagealphamask($image,$mask);
		
		if($image!=FALSE){
			
			$box = $this->getBoundingBox();
			
			$cropedimage =imagecrop($image,$box);
			
			if ($cropedimage !== FALSE) {
				

				$path = 'data/png/'.$this->area_id.'.png';
				
				imagepng($cropedimage,$path);
				//imagepng($cropedimage,$path);
				
				echo '<img src="'.$path .'">';
			}			
			
		}
			
	}
	
	public function imagealphamask( &$picture, $mask ) {
		set_time_limit(500);
		// Get sizes and set up new picture
		$xSize = imagesx( $picture );
		$ySize = imagesy( $picture );
		$newPicture = imagecreatetruecolor( $xSize, $ySize );
		imagesavealpha( $newPicture, true );
		imagefill( $newPicture, 0, 0, imagecolorallocatealpha( $newPicture, 255, 255, 255, 127 ) );

		/* Resize mask if necessary
		if( $xSize != imagesx( $mask ) || $ySize != imagesy( $mask ) ) {
			$tempPic = imagecreatetruecolor( $xSize, $ySize );
			imagecopyresampled( $tempPic, $mask, 0, 0, 0, 0, $xSize, $ySize, imagesx( $mask ), imagesy( $mask ) );
			imagedestroy( $mask );
			$mask = $tempPic;
		}*/

		// Perform pixel-based alpha map application
		for( $x = 0; $x < $xSize; $x++ ) {
			for( $y = 0; $y < $ySize; $y++ ) {
				$alpha = imagecolorsforindex( $mask, imagecolorat( $mask, $x, $y ) );
				$alpha = 127 - floor( $alpha[ 'red' ] / 2 );
				$color = imagecolorsforindex( $picture, imagecolorat( $picture, $x, $y ) );
				imagesetpixel( $newPicture, $x, $y, imagecolorallocatealpha( $newPicture, $color[ 'red' ], $color[ 'green' ], $color[ 'blue' ], $alpha ) );
			}
		}

		// Copy back to original picture
		imagedestroy( $picture );
		$picture = $newPicture;
		
		
	}	
	
}




?>