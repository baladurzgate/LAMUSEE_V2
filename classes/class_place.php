<?php
class place extends LMObject
{
	public $name;
	public $description;
	public $coords;
	
	public function __construct($param) { 
	
		parent::__construct();

		$this->LMClass = "place";
	
		$this->add_property("name","mediumtext");
		$this->add_property("coords","mediumtext");	
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
	
}


?>