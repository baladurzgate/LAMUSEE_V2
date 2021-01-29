<?php
class period extends LMObject
{
	public $name;
	public $first_date;
	public $last_date;
	public $duration; 
	
	public function __construct($param) { 
	
		parent::__construct();

		$this->LMClass = "period";
	
		$this->add_property("name","mediumtext");
		$this->add_property("first_date","mediumtext");	
		$this->add_property("last_date","mediumtext");	
		$this->add_property("duration","mediumtext");	
		
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
		
		$this->duration = intval($this->last_date)-intval($this->first_date);

	}
	
	
	public function is_in_period($date){
		
		if($this->first_date <= $date && $this->last_date>=$date){
				return true; 
		}
		return false;
	}
	
}


?>