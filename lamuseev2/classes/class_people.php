<?php
//People
class people extends LMObject
{
	public $name;
	public $period;
	public $place_of_birth;
	public $country;
	public $profession;
	public $work_place;
	public $biography;

		public function __construct($param) { 
		
			parent::__construct();
			
			$this->period = array();
			$this->setKeyProperty('name');
		
			$this->LMClass= "people";
			$this->add_property("name","mediumtext");
			$this->add_property("period","mediumtext","date",true);
			$this->add_property("place_of_birth","mediumtext","place");
			$this->add_property("country","mediumtext","region");
			$this->add_property("profession","mediumtext");
			$this->add_property("work_place","mediumtext","place");
			$this->add_property("biography","mediumtext","text");
				
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