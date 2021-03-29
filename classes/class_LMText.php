<?php
class LMText extends LMObject
{
	public  $wp_id; 
	public  $name;
	public  $nice_name;
	public  $content;
	public 	$translations;
	public 	$language;

		public function __construct($param) { 
		
			parent::__construct();
			
			$this->LMClass= get_class($this);
			
			$this->publishing_date =array();
			
			$this->add_property("name","mediumtext");
			$this->add_property("nice_name","mediumtext");
			$this->add_property("content","mediumtext");
			$this->add_property("translations","mediumtext","LMText",true);
			
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