<?php
class LMEntity
{

    private $LMID;
    private $LMClass;
	public  $name
	public  $LMtimestamp;
	
	public function __construct(){ 

		
		$this->properties = array();
		$this->LMClass = "LMEntity"; 
		
	}
	
	public function get_ID(){
		
		return $this->LMID;
		
	}
	public function get_LMClass(){
		
		return $this->LMClass;
		
	}	
	public function set_LMtimestamp($t){
		
		$this->LMtimestamp = $t;
		
	}	
}

?>