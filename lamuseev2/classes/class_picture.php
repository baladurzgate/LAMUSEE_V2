<?php
//Picture
class picture extends LMObject
{
	public  $wp_id; 
	public  $name; 
	public  $file_path;
	public  $width;
	public  $height;
	public  $size;
	public  $thumbnail_image_path;
	public  $highres_image;
	public  $areas;
	public  $map_scale;
	public  $map_offset_x;
	public  $map_offset_y;
	public  $dimentions;



		public function __construct($param) { 
		
			parent::__construct();

			$this->setKeyProperty('file_path');
			

			$this->LMClass= get_class($this);
			
			$this->$areas = array();
			
			$this->add_property("name","mediumtext");
			$this->add_property("wp_id","mediumtext");
			$this->add_property("file_path","mediumtext","file");
			$this->add_property("width","mediumtext");
			$this->add_property("height","mediumtext");
			$this->add_property("size","mediumtext");
			$this->add_property("thumbnail_image_path","mediumtext","file");
			$this->add_property("areas","mediumtext","area",true);
			$this->add_property("map_scale","mediumtext");
			$this->add_property("map_offset_x","mediumtext");
			$this->add_property("map_offset_y","mediumtext");
			$this->add_property("dimensions","mediumtext");
			$this->add_property("highres_image","mediumtext","picture");
				
			echo "NEW PAINTING";
			
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

}



?>