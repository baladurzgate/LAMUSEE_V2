<?php

class shape extends LMObject{


	public $shape_ID;
	public $shape_name;
	public $shape_creation_date;
	public $shape_last_modification;
	public $shape_nice_name;
	public $shape_paintings_list;
	public $shape_clicks;

	public function __construct($param) { 
	
		parent::__construct();
		
		
		$this->LMClass = "shape";
		
		//to be serialised
		$this->shape_paintings_list = array();
	
		$this->add_property("shape_name","mediumtext");
		$this->add_property("shape_nice_name","mediumtext");
		$this->add_property("shape_paintings_list","mediumtext","painting",true);
		
		$this->setKeyProperty("shape_name");
	
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
	
	public function add_painting($p){
	
			if (!in_array($p, $this->shape_paintings_list)) {
				array_push($this->shape_paintings_list,$p);
			}
		
	}
	
	public function choose_random_painting($current_p=false){
		
		$random_pool =Array();;
		
		foreach($this->shape_paintings_list as $p){
		
			if($p != $current_p){
				array_push($random_pool,$p);
				
			}
			
		}

		if(count($random_pool)>1){
			
			
			$random_index = array_rand($random_pool,1);
			
			return $random_pool[$random_index];			
			
		}
		
		return false;	
		
	}
	
	
	public function getName (){
	
		return $this->shape_name;
	
	}	
	
	public function setName ($n){
	
		$this->shape_name = $n;	
	
	}

	public function getNiceName (){
	
		return $this->shape_nice_name;
	
	}
	
	public function setNiceName ($nn){
	
		$this->shape_nice_name = $nn;	
	
	}


}



?>