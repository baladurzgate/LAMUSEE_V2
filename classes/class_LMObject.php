<?php
class LMObject
{

    public $LMID;
	public $properties;
	public $LMClass;
	public $KeyProperty;
	public $LMtimestamp;
	
	//TO DO , every properties should be called LMproperties and use getter and setters. change and retreive their values
	
	
	public function __construct(){ 

		
		$this->properties = array();
		$this->LMClass = "LMObject"; 
		$this->add_property("LMID","mediumtext");
		$this->add_property("LMtimestamp","mediumtext");

		//par default 
		$this->setKeyProperty("LMID");
		
		
	}
	
	public function setLMID($id){
		
		$this->LMID = $id;
		
	}
	
	//varies according to the class. point to the property used to compare instances (ex: "name" for the class "people") define its uniqness beside the LMID
	public function setKeyProperty($kp){
		
		$this->KeyProperty = $kp;
		
	}
	public function getKeyPropertyValue(){
		
		$kp = $this->KeyProperty;
		return $this->$kp;
		
	}
	
	
	public function property_exist($pn){
	
		foreach ($this->properties as $prop){
			if($prop->name == $pn){
					return true;
			}
		}	
		return false;
	}
	
	public function add_property($pname,$ptype,$called_class="",$isArray=false){
	
		$p = new LMProperty($pname,$ptype,$called_class,$isArray); 
		
		if($this->properties == null){
			$this->properties = array();
		}
		
		if($this->property_exist($pname)==false){
			$this->properties[$pname]=$p;
			return $p;
		}else{
			
		}
		
		
		
	}
	
	
	
	public function get_value_of($propname,$stringoutput=false){
	
		$p = $this->properties[$propname];

		$result= $this->$propname;
		
		if($p->isArray==true ||gettype($result)=='array'){
			
			if(gettype($result)=="array" && $stringoutput==true){

				$result= json_encode($this->$propname);
				
			}
			
		}else{

			$result = addslashes($result);

			
		}
		
		
		return $result;
		

	}
	
	public function set_property_value($pn,$v){
		$p = $this->properties[$pn];
	}
	
	public function get_properties_string(){
	
		$str = "";
		$number_of_p = sizeof($this->properties);
		$i=0;
		foreach($this->properties as $p){
			$p_name = $p->name;
			$coma = ", ";
			if($i == $number_of_p-1){
				$coma = " ";
			}
			$str.=$p_name.$coma;
			$i++;
			
		}
		
		return $str;
		
	}
	

	
	public function get_values_string(){
	
		$str = "'";
		$number_of_p = sizeof($this->properties);
		$i=0;
		foreach($this->properties as $p){
			$p_name = $p->name;
			$v= $this->get_value_of($p_name,true);
			$coma = "', '";
			if($i == $number_of_p-1){
				$coma = "'";
			}

			$str.=$v.$coma;
			$i++;
		}
		
		return $str;
		
	}
	
	
	public function get_sql_SET_string(){
	
		$str = "";

		foreach($this->properties as $p){
			
			$p_name = $p->name;
			
			$v= $this->get_value_of($p_name,true);
			
			$equality = $this->get_property_sql_SET_string($p_name);

			$str.=$equality;

		}
		
		return $str;
		
	}
	
	public function get_property_sql_SET_string($prop){
	
		$str = "";

		$p_name = $prop;
			
		$v= $this->get_value_of($p_name,true);
			
		$equality = " ".$p_name." = '".$v."' ";

		$str=$equality;

		return $str;
		
	}
		
		
	public function get_json_properties(){
	
		return json_encode($this->properties);
		
	}	
	
	public function update_properties($params){
		
		
		
	}
	
	

	// a quick view method. 
	
	public function display_in_html(){
		
		echo "<div style='border: 1px solid black; background-color: white;'>";
		
		echo "<br>";
		
		echo '<table><thead><tr>';
		echo '<th><h2>'.$this->LMClass.'</h2></th>';


		echo '</tr></thead><tbody>';

		foreach($this->properties as $p){
			$propname = $p->name;
			$value = $this->get_value_of($propname,true);
			$reduced_string = substr($value, 0, 100); 
			echo '<tr>';
				echo '<td><b>'.$propname.'  : </b></td>';
				echo '<td>'.$reduced_string.'</td>';
			echo '</tr>';
		}
		echo '</tbody></table>';
		

		
		echo "</div>";
		
	}
	
}

?>