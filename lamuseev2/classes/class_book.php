<?php
// BOOK
class book extends LMObject
{
	public  $title; 
	public  $author;
	public  $publishing_date;
	public  $linked_texts;



		public function __construct($param) { 
		
			parent::__construct();

			
			$this->LMClass= get_class($this);

			
			$this->add_property("title","mediumtext");
			$this->add_property("author","mediumtext","people");
			$this->add_property("publishing_date","mediumtext","LMdate",true);
			$this->add_property("linked_texts","mediumtext","text",true);
			
			$this->setKeyProperty('title');
			
			$this->linked_texts = array();
			
			echo "NEW TEXT";
			
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
	
	public function link_text($t){
		if($t!=null){
			
			if(in_array($t, $this->linked_texts)==false){
				array_push($this->linked_texts,$t);
			}

		}
		
	}

}




?>