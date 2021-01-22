<?php
class region extends LMObject
{
	public $name;
	public $type;
	public $description;
	public $coords;
	public $area;
	public $linked_places; 
	public $linked_regions; 
	
	
	public function __construct($param) { 
	
		parent::__construct();
		
		$this->LMClass = "region";
	
		$this->add_property("name","mediumtext");
		$this->add_property("type","mediumtext");
		$this->add_property("description","mediumtext");
		$this->add_property("area","mediumtext");
		$this->add_property("linked_places","mediumtext","place",true);
		$this->add_property("linked_regions","mediumtext","region",true);
		
		$this->linked_regions = array();
		$this->linked_places = array();
		
		$this->setKeyProperty('name');
	
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
	
	public function link_region($r){
		if($r!=null){
			
			if(in_array($r, $this->linked_regions)==false){
				array_push($this->linked_regions,$r);
			}

		}
		
	}

}



?>