<?php
	class LMproperty
	{

		public $name;
		public $type;
		public $value;
		public $called_class; 
		public $isArray;
		
		
		public function __construct($n,$t,$cc,$ia){ 
		
			$this->name = $n;
			$this->type = $t;
			$this->called_class = $cc;
			$this->isArray = $ia;
		
		}
		
		public function html_input(){
			
			echo "";
			
		}
		
		public function html_output(){
			
			echo "";
			
		}	
	}
?>