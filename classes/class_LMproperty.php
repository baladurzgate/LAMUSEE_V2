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
		
		public function get_html_input($lm,$o){
			
			$pname = $this->name;
		
			$value = $o->$pname;
			
			//put everithing in hidden mode and just make inputs with js ? 
			
			echo '<br>'.$pname.' : <input type="text" id="'.$pname.'" name="'.$pname.'" list="'.$pname.'_list">';
			
			if($this->called_class!=""){
				

				$class_plurial = $this->called_class."s";				
				
				$instances_list = $lm->$class_plurial;
				
				//need to remove self from the list
					
				
				
				echo '<datalist id="'.$pname.'_list">';
				
				foreach($instances_list as $i){
					echo '<option value="'.$i->LMID.'">'.$i->getKeyPropertyValue().'</option>';
				}

				echo '</datalist>';
				
				if(gettype($value)=="array"){
					
					//some js ? 
					
				}
			
			}
			
		}
		
		public function get_html_output($lm,$o){
			
			$pname = $this->name;
		
			$value = $o->$pname;
			
			echo '<br>';
			echo '<b>----- '.$pname.'</b> ' ;
			echo '<br>';
			
			if($value!=""){

				if($this->called_class!=""){
					
					if(gettype($value)=="array"){
						
						foreach($value as $v){
							
							if($this->called_class=='image'){
								
								echo $this->get_image_html($v);
								
							}else{

								$linked_obj = $lm->find_lmobject($v);
								
								if($linked_obj!=false){
									echo  $lm->get_html_link($linked_obj);				
								}
						
							}
							
							echo "<br>";

						}
						
					}else{
						
						if($this->called_class=='image'){
							
							echo $this->get_image_html($value);
							
						}else{
							
							$linked_obj = $lm->find_lmobject($value);
							
							if($linked_obj!=false){
								
								echo  $lm->get_html_link($linked_obj);	
								
							}
						}

						
					}

				}else{
					if(get_class($o) == "area" && $pname == "area_coords"){
						$o->getPng();
					}else{
						echo  $value;				
						
					}
				}
			
			}
				
			
		}	
		
		public function get_image_html($path){
			return '<img src="'.$path.'">';
		}
		
		

	}
?>