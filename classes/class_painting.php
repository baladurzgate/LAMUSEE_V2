<?php
//class Painting
class painting  extends LMObject
{
	public  $wp_id; 
	public  $name;
	public  $nice_name;
	public  $pictures;
	public  $linked_shapes;
	public  $linked_texts;
	public  $artiste;
	public  $titre_du_tableau;
	public  $technique;
	public  $creation_date;
	public  $lieu_de_conservation;
	public  $pays;
	public  $region;
	public  $artiste2;



		public function __construct($param) { 
		
			parent::__construct();

			$this->setKeyProperty('name');
			$this->LMClass= get_class($this);
			
			$this->add_property("wp_id","mediumtext");
			$this->add_property("name","mediumtext");
			$this->add_property("nice_name","mediumtext");
			$this->add_property("linked_shapes","mediumtext","shape",true);
			$this->add_property("pictures","mediumtext","picture",true);
			$this->add_property("linked_texts","mediumtext","text",true);
			$this->add_property("artiste","mediumtext","people");
			$this->add_property("technique","mediumtext");
			$this->add_property("creation_date","mediumtext");
			$this->add_property("lieu_de_conservation","mediumtext","place");
			$this->add_property("pays","mediumtext","region");
			$this->add_property("region","mediumtext","region");
			$this->add_property("artiste2","mediumtext","people");
			
			$this->linked_shapes = array();
			$this->creation_date = array();
			$this->linked_texts = array();
			
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