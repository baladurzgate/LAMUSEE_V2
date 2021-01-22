<?php
class text extends LMObject
{
	public  $wp_id; 
	public  $name;
	public  $nice_name;
	public  $content;
	public  $author;
	public  $translator;
	public  $publishing_date;
	public  $linked_book;

		public function __construct($param) { 
		
			parent::__construct();
			
			$this->LMClass= get_class($this);
			
			$this->publishing_date =array();
			
			$this->add_property("wp_id","mediumtext");
			$this->add_property("name","mediumtext");
			$this->add_property("nice_name","mediumtext");
			$this->add_property("content","mediumtext");
			$this->add_property("author","mediumtext","people");
			$this->add_property("translator","mediumtext","people");
			$this->add_property("publishing_date","mediumtext","LMdate",true);
			$this->add_property("linked_book","mediumtext","book");

			$this->publishing_date=array();

			$this->setKeyProperty('name');
			
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

}



?>