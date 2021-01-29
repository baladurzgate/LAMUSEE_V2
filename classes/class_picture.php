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
	public  $dimensions;



		public function __construct($param) { 
		
			parent::__construct();

			$this->setKeyProperty('name');
			

			$this->LMClass= get_class($this);
			
			$this->areas = array();
			
			$this->add_property("name","mediumtext");
			$this->add_property("wp_id","mediumtext");
			$this->add_property("file_path","mediumtext","image");
			$this->add_property("width","mediumtext");
			$this->add_property("height","mediumtext");
			$this->add_property("size","mediumtext");
			$this->add_property("thumbnail_image_path","mediumtext","image");
			$this->add_property("areas","mediumtext","area",true);
			$this->add_property("map_scale","mediumtext");
			$this->add_property("map_offset_x","mediumtext");
			$this->add_property("map_offset_y","mediumtext");
			$this->add_property("dimensions","mediumtext");
			$this->add_property("highres_image","mediumtext","picture");
			
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
	
	public function get_html(){
		return '<img src="'.$this->file_path.'">';
	}
	
	public function get_thumbnail_html(){
		return '<img src="'.$this->thumbnail_image_path.'">';
	}
	


}



?>