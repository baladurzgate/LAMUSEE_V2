<?php
class LMDate extends LMObject

	public $time;
	
	public function __construct($param) { 
	
		parent::__construct();

		$this->LMClass = "period";
	
		$this->add_property("time","mediumtext");
		$this->setKeyProperty('time');
	
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