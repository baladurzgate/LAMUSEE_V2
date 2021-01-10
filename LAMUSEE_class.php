
<?php
/* V LOCAL */


include "LAMUSEE_DBconnect.php";


// //************************************************************** MOTHER CLASS

global $lmdb; 







class Lamusee{

	public $shapes;
	public $areas;
	public $paintings;
	public $picture;
	public $texts;
	public $books;
	public $artists;
	public $peoples;
	public $dates;
	public $periods;
	public $places;
	public $points;
	public $regions;
	public $articles;
	
	
	public $LMtables; 
	
	public function __construct() { 
	
		$this->LMobjects = array();
		$this->shapes = array();
		$this->areas = array();
		$this->paintings = array();
		$this->pictures = array();
		$this->texts = array();
		$this->artists = array();	
		$this->peoples = array();	
		$this->dates = array();	
		$this->places = array();	
		$this->periodes = array();	
		$this->regions = array();	
		$this->articles = array();
		$this->books = array();

	
	}
	
	
	
	public function init(){
		
			
	}
	
	
	public function load_tables(){
		
		global $lmdb;
		$lmdb = OpenLamuseeDB();
		
		$people = new people(array());
		$shape = new shape(array());
		$area = new area(array());
		$place = new place(array());
		$region = new region(array());
		$painting = new painting(array());	
		$picture = new picture(array());		
		$book = new book(array());		
		$text = new text(array());		
		
		$this->load_table($people);
		$this->load_table($area);
		$this->load_table($place);
		$this->load_table($region);
		$this->load_table($painting);
		$this->load_table($picture);
		$this->load_table($text);
		$this->load_table($book);

				
		
	}
	
	public function create_tables(){
		
		global $lmdb;
		$lmdb = OpenLamuseeDB();
		
		
		$people = new people(array());
		$shape = new shape(array());
		$area = new area(array());
		$region = new region(array());
		$place = new place(array());
		$painting = new painting(array());
		$picture = new picture(array());
		$book = new book(array());
		$text = new text(array());

		$picture->build_table();
		$people->build_table();
		$shape->build_table();
		$area->build_table();
		$place->build_table();
		$region->build_table();
		$painting->build_table();
		$book->build_table();
		$text->build_table();

	}
	
	public function update_tables(){
		
		global $lmdb;
		$lmdb = OpenLamuseeDB();
		
		$people = new people(array());
		$shape = new shape(array());
		$area = new area(array());
		$place = new place(array());
		$region = new region(array());
		$painting = new painting(array());		
		$picture = new picture(array());
		$book = new book(array());
		$text = new text(array());
		
		$this->update_table($area);
		$this->update_table($shape);
		$this->update_table($people);
		$this->update_table($place);
		$this->update_table($region);
		$this->update_table($painting);
		$this->update_table($picture);
		$this->update_table($book);
		$this->update_table($text);
		
		
	}
	
	
	public function display_tables(){
		
		$people = new people(array());
		$shape = new shape(array());
		$area = new area(array());
		$place = new place(array());
		$region = new region(array());
		$painting = new painting(array());	
		$picture = new picture(array());	
		$book = new book(array());		
		$text = new text(array());
		
		$this->display_lmobj_list($area);
		$this->display_lmobj_list($shape);
		$this->display_lmobj_list($people);
		$this->display_lmobj_list($place);
		$this->display_lmobj_list($region);
		$this->display_lmobj_list($picture);
		$this->display_lmobj_list($painting);
		$this->display_lmobj_list($text);
		$this->display_lmobj_list($book);


	}
	
	public function display_lmobj_list($obj){
		
		$class = get_class($obj);
		
		$class_plurial = $class."s";
		
		foreach($this->$class_plurial as $o){
			$o->display_in_html();
		}		
	}
	
	public function update_table($obj){
		
		global $lmdb;
		$lmdb = OpenLamuseeDB();
		
		$class = get_class($obj);
		
		$class_plurial = $class."s";

		$table_name = "lamusee_".$class_plurial;
		
		foreach ($this->$class_plurial as $o){
		
			// check if the object is already stored in the db by its  LMID

			$sql = "SELECT * FROM ".$table_name." WHERE LMID = '".$o->LMID."'";
			
			$result = $lmdb->query($sql);
			
			if ($result->num_rows > 0) {

			  while($row = $result->fetch_assoc()) {
				  
				 "<br>-------EXIST IN DB------<br>";
				
				$params = $row;
				//$lm->loadObject($class,$params);
				
				// we update the modified entries 
				
			  }
				
			 }else {
				
				// we create the entry for this object
				
				$prop_str = $o->get_properties_string();
				
				$value_str = $o->get_values_string();
				
				$sql = "INSERT INTO ".$table_name." (".$prop_str.") VALUES (".$value_str.")";
				
				echo '<br>';
				print_r($sql);
				echo '<br>';
				
				$lmdb->query($sql);			 
			}

		}

		
	}
	
	public function load_table($obj){
		
		global $lmdb;
		
		$lmdb = OpenLamuseeDB();
		
		$class = get_class($obj);
		
		$class_plurial = $class."s";

		$table_name = "lamusee_".$class_plurial;	

		$sql = "SELECT * FROM ".$table_name."";
			

		foreach( $lmdb->query($sql) as $params) {
			
			$this->loadObject($class,$params);
			
		}

		
		
	}
	
	public function add_or_call_object($class,$params,$return_object=false){
		
		$useful_lmid ="";
		
		echo "------------------------$class------------------------";
		
		$test_lmobj = new $class($params);
		
		$stored_lmobj = $this->alreadyExist($test_lmobj);
		
		if($stored_lmobj == false){
			
				$new_lmobj = $this->addObject($class,$params);
				
				$useful_lmid = $new_lmobj->LMID;
				
				if($return_object){
					return $new_lmobj;
				}
				
		}else{
			$useful_lmid = $stored_lmobj->LMID;
			
			if($return_object){
				return $stored_lmobj;
			}
		}
		
		
		
		return $useful_lmid;
	}
	
	public function find_lmobject($LMID){
		
		$explode = explode($LMID,"-");
		$class = substr($explode[0],2);
		$index = $explode[1];
		
		return $this->$class[$index];
		
	}
	
	
	public function generate_serial($obj){
		
		
		$class = get_class($obj);

		$arrayname = $class."s";
		
		$keyp = $obj->KeyProperty;
		
		$key_value= $obj->$keyp;
		
		
		
		//$serial = "LM".$class.sizeof($this->$arrayname)."-".strlen($key_value).rand(000,100);
		$serial = "LM".$class."-".sizeof($this->$arrayname);

		return $serial;
		
	}
	
	
	public function addObject($LMClass,$properties){
		
		// warning this function does not check for duplicates before pushing
			
		$arrayname = $LMClass."s";
		
		$nObj = new $LMClass($properties);

		$serialnumber = $this->generate_serial($nObj);
		
		$keyp = $nObj->KeyProperty;
		
		$key_value= $nObj->$keyp;
		
		$LMID = $serialnumber;
		
		$nObj->timestamp = time();
		
		$nObj->setLMID($LMID);
		
		array_push($this->$arrayname,$nObj);	
			
		return $nObj;

	}
	
	public function loadObject($LMClass,$properties){
		
		// warning this function does not check for duplicates before pushing
		
		$arrayname = $LMClass."s";
		$nObj = new $LMClass($properties);
		
		$objmatch = $this->alreadyExist($nObj);
		
		//if ($objmatch==false){

		array_push($this->$arrayname,$nObj);	
			
		//}else{
			
			
		//}
			
	}
	
	/* useful function to convert the old db to v2
	compare key properties of an LMobj , the keyproperty is by default "LMID" but can be changed in sub class declarations.  
	for exmple : for the class "people" the property to compare is "name"
	
	*/
	
	
	public function alreadyExist($obj){
	
		$keyp = $obj->KeyProperty;
		$LMClass = $obj->LMClass;
		$arrayname = $LMClass."s";
		
		$match = 0;
		
		$valuetocompare = $obj->$keyp;
		
		foreach ($this->$arrayname as $o){
			
			if($o->$keyp == $valuetocompare){
				
					return $o;
			}
			
			$match++;
				
		}
		
		if($match == 0){
			
			return false; 
		}
	
	}


	
	public function parse_painting_areas_with_random_links($post_id){
		
		$html = "";
	
		foreach ($this->areas as $area){
			
			if($area->getPainting() == $post_id){
				
				$shape = $this->getShapeByName($area->getShapeName());

				$area_tag = $area->areaToHTML("blabla");
				
				$html = $html.$area_tag;
			
			}
				
		
		}	
		
		//echo $html;
		
		return $html;
	
	
	}

}


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
	
}

class LMObject
{

    public $LMID;
	public $properties;
	public $LMClass;
	public $KeyProperty;
	public $timestamp;
	
	
	public function __construct(){ 

		
		$this->properties = array();
		$this->LMClass = "LMObject"; 
		$this->add_property("LMID","mediumtext");
		$this->add_property("timestamp","mediumtext");

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
		
		if($p->isArray==true){
			
			if(gettype($result)=="array" && $stringoutput==true){

				$result= json_encode($this->$propname);
				
			}
			
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
		
	
	public function build_table() {
		
		global $lmdb;
		
		if($lmdb == null){
			
			$lmdb = OpenLamuseeDB();
		}
		
  		$table_name = "lamusee_".$this->LMClass."s";

		if($lmdb->query("DESCRIBE '$table_name'") == FALSE) 
		{
			
			$sql = "CREATE TABLE " . $table_name . " (`id` mediumint(9) NOT NULL AUTO_INCREMENT,";
			
			foreach ($this->properties as $p){
				
				echo "____".$p->name."_____";
				
				$sql.="`".$p->name."` ".$p->type." NOT NULL,";
			
			}
			
			$sql.="UNIQUE KEY id (id));";
			
			echo "<br>";
			print_r($sql);
			echo "<br>";

			$lmdb->query($sql);
			
		}
	}	
	
	
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


class LMName extends LMObject
{
	
	

}


//  //************************************************************** TIME CLASSES


class LMdate extends LMObject
{
	private $value;
	private $comment;
	
	public function __construct($value,$comment){ 
	
		$this->value = $name;
		$this->comment = $comment;
	
	}
	

}



class period extends LMObject
{

	private $name;
	private $begining_date;
	private $end_date;
	private $format;
	
	public function __construct($name,$begining_date,$end_date,$format){ 
	
		$this->setKeyProperty('name');
	
		$this->name = $name;
		$this->begining_date = $begining_date;
		$this->end_date = $end_date;
		$this->formate = $format;
		
		$this->table = "period";
		
		$this->add_property("name","mediumtext");
		$this->add_property("begining_date","mediumtext");
	
	}

    public function check_date() {
        
    }
	
}

class LM_Event extends LMObject
{

	private $name;
	private $event_date;
	
	public function __construct($name,$event_date){ 
	
		$this->name = $name;
		$this->event_date = $event_date;
		
		$this->setKeyProperty('event_date');
	
	}
	
	
}




////**************************************************************  SPACE CLASSES


class place extends LMObject
{
	public $name;
	public $description;
	public $coords;
	
	public function __construct($param) { 
	
		parent::__construct();
	
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

		$this->LMClass = "place";
	
		$this->add_property("name","mediumtext");
		$this->add_property("coords","mediumtext");	
		$this->setKeyProperty('name');
		
	}
	
}


class region extends LMObject
{
	public $name;
	public $type;
	public $description;
	public $coords;
	public $area;
	public $linked_places; 
	public $linked_regions; 
	
	
	public function __construct($param) { 
	
		parent::__construct();
		
		$this->LMClass = "region";
	
		$this->add_property("name","mediumtext");
		$this->add_property("type","mediumtext");
		$this->add_property("description","mediumtext");
		$this->add_property("area","mediumtext");
		$this->add_property("linked_places","mediumtext","place",true);
		$this->add_property("linked_regions","mediumtext","region",true);
		
		$this->linked_regions = array();
		$this->linked_places = array();
		
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
	
	public function link_region($r){
		if($r!=null){
			
			if(in_array($r, $this->linked_regions)==false){
				array_push($this->linked_regions,$r);
			}

		}
		
	}

}


// //**************************************************************  CLASSES



//People
class people extends LMObject
{
	public $name;
	public $period;
	public $place_of_birth;
	public $country;
	public $profession;
	public $work_place;
	public $biography;

		public function __construct($param) { 
		
			parent::__construct();
			
			$this->period = array();
			$this->setKeyProperty('name');
		
			$this->LMClass= "people";
			$this->add_property("name","mediumtext");
			$this->add_property("period","mediumtext","date",true);
			$this->add_property("place_of_birth","mediumtext","place");
			$this->add_property("country","mediumtext","region");
			$this->add_property("profession","mediumtext");
			$this->add_property("work_place","mediumtext","place");
			$this->add_property("biography","mediumtext","text");
				
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

//Picture
class picture extends LMObject
{
	public  $wp_id; 
	public  $name; 
	public  $lowres_image_path;
	public  $width;
	public  $height;
	public  $size;
	public  $highres_image_path;
	public  $thumbnail_image_path;
	public  $areas;
	public  $map_scale;
	public  $map_offset_x;
	public  $map_offset_y;
	public  $dimentions;



		public function __construct($param) { 
		
			parent::__construct();

			$this->setKeyProperty('lowres_image_path');
			

			$this->LMClass= get_class($this);
			
			$this->$areas = array();
			
			$this->add_property("name","mediumtext");
			$this->add_property("wp_id","mediumtext");
			$this->add_property("lowres_image_path","mediumtext","file");
			$this->add_property("width","mediumtext");
			$this->add_property("height","mediumtext");
			$this->add_property("size","mediumtext");
			$this->add_property("highres_image_path","mediumtext","file");
			$this->add_property("thumbnail_image_path","mediumtext","file");
			$this->add_property("areas","mediumtext","area",true);
			$this->add_property("map_scale","mediumtext");
			$this->add_property("map_offset_x","mediumtext");
			$this->add_property("map_offset_y","mediumtext");
			$this->add_property("dimensions","mediumtext");
				
			echo "NEW PAINTING";
			
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

//Painting
class painting  extends LMObject
{
	public  $wp_id; 
	public  $name;
	public  $nice_name;
	public  $picture;
	public  $linked_shapes;
	public  $linked_texts;
	public  $artiste;
	public  $titre_du_tableau;
	public  $technique;
	public  $creation_date;
	public  $lieu_de_conservation;
	public  $pays;
	public  $region;
	public  $artiste2;



		public function __construct($param) { 
		
			parent::__construct();

			$this->setKeyProperty('name');
			$this->LMClass= get_class($this);
			
			$this->add_property("wp_id","mediumtext");
			$this->add_property("name","mediumtext");
			$this->add_property("nice_name","mediumtext");
			$this->add_property("linked_shapes","mediumtext","shape",true);
			$this->add_property("picture","mediumtext","picture");
			$this->add_property("linked_texts","mediumtext","text",true);
			$this->add_property("artiste","mediumtext","people");
			$this->add_property("technique","mediumtext","technique");
			$this->add_property("creation_date","mediumtext","date",true);
			$this->add_property("lieu_de_conservation","mediumtext","place");
			$this->add_property("pays","mediumtext","region");
			$this->add_property("region","mediumtext","region");
			$this->add_property("artiste2","mediumtext","people");
			
			$this->linked_shapes = array();
			$this->creation_date = array();
			$this->linked_texts = array();
			
			echo "NEW PAINTING";
			
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
	}

}


// BOOK
class book extends LMObject
{
	public  $title; 
	public  $author;
	public  $publishing_date;
	public  $linked_texts;



		public function __construct($param) { 
		
			parent::__construct();

			$this->setKeyProperty('title');
			
			$this->linked_texts =array();
			
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

		$this->LMClass= get_class($this);
		
		
		
		$this->add_property("title","mediumtext");
		$this->add_property("author","mediumtext","people");
		$this->add_property("publishing_date","mediumtext","LMdate",true);
		$this->add_property("linked_texts","mediumtext","text",true);
		
		$this->linked_texts = array();

		
	}
	
	public function link_text($t){
		if($t!=null){
			
			if(in_array($t, $this->linked_texts)==false){
				array_push($this->linked_texts,$t);
			}

		}
		
	}

}



//************************************************************** GRAPHIC CLASSES


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

		
		
		$this->LMClass = "shape";
		
		$this->setKeyProperty("shape_name");
		
		
		//to be serialised
		$this->shape_paintings_list = array();
	
		$this->add_property("shape_name","mediumtext");
		$this->add_property("shape_nice_name","mediumtext");
		$this->add_property("shape_paintings_list","mediumtext","painting",true);
		
	
	}
	
	public function add_painting($p){
	
			array_push($this->shape_paintings_list,$p);
		
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


class area extends LMObject{
	
	public  $area_shape_name;
	public  $area_shape_type;
	public  $area_nice_name;
	public  $area_coords;
	public  $area_painting;
	public  $area_id;

	
	public function __construct($param) { 
	
		parent::__construct();
			
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


		$this->LMClass = "area";
		
		//attention a ne pas mettre d'espaces dans les string!! cela donne une erreur SQL "
		
		$this->add_property("area_shape_name","mediumtext");
		$this->add_property("area_shape_type","mediumtext");
		$this->add_property("area_nice_name","mediumtext");
		$this->add_property("area_coords","mediumtext");
		$this->add_property("area_painting","int");
		$this->add_property("area_id","mediumtext");
	
	}
	
	
	public function getShapeName(){ return $this->area_shape_name;}
	public function setShapeName($sn){ $this->area_shape_name = $sn;}
		
	public function getShapeType(){ return $this->area_shape_type;}
	public function setShapeType($st){ $this->area_shape_type = $st;}
	
	public function getCoords(){ return $this->area_coords;}
	public function setCoords($c){ $this->area_coords = $c;}
	
	public function getNiceName(){ return $this->area_nice_name;}
	public function setNiceName($nn){ $this->area_nice_name = $nn;}
	
	
	public function getPainting(){ return $this->area_painting;}
	public function setPainting($p){ $this->area_painting = $p;}
	
	
	public function getID(){ return $this->area_id;}
	public function setID($i){ $this->area_id = $i;}
	
	public function areaToHTML($_link){
		
		$link = $_link != undefined && $_link != null ? $_link : '#'.$this->area_shape_name;
	
		$HTML	=  '<area shape="'.$this->area_shape_type.'" coords="'.$this->area_coords.'" href="'.$link.'" alt="'.$this->area_shape_name.'" title = "'.$this->area_nice_name.'"  areaID ="'.$this->area_id.'">'."\n";
		return $HTML;
		
	}

}



/******************************************/

function romanCenturyToYear($str){

    $array = str_split($str);
    $i = 0;
    $century = 0;
    
    foreach($array as $letter){
        $n = $array[$i]= convertToArab($letter);
        if($i==0){
         $century=$n;
        }
        if($i>0){
            $pn =  $array[$i-1];
            if( $pn<$n){
                $century+= $n-$pn;
                if($pn==1){
                    $century--;
                }
            }
             if( $pn>=$n){
                $century += $n;
            }          
        }
        $i++;
    }
    
    return ($century-1)*100;


}

function convertToArab($rn){

    if($rn=="I"){
        return 1;
    }
    if($rn=="V"){
        return 5;
    }
    if($rn=="X"){
        return 10;
    }
}


function extract_date($str){

	$extracted_dates = array(); 
	/*single date*/
	if(preg_match('#^[0-9]*$#',$str)&&!preg_match('#((?![0-9-]).)+#',$str)){
		array_push($extracted_dates,$str);
	}
	
	/*century in roman  number*/
	if(preg_match('#[XVI]+[er]#', $str)){
		preg_match_all('#[XVI]+[er]#', $str, $romanDate);
		foreach( $romanDate[0] as $rd){
			$year = romanCenturyToYear($rd);
			$begining = $year+1;
			$end  = $year+100;
			
			if(preg_match('#avant J.–C#', $str)){
				$begining=($year+100)*-1;
				$end= ($year+1)*-1;
			}
			
			array_push($extracted_dates,$begining);
			array_push($extracted_dates,$end );

		}	

	}		
	
	/*date integrarted in text with semicolumn ex : Lorenzo Lotto (1480-1557)*/
	$period_match = "";
	if(preg_match('#\((.*?)\)#', $str)){
		preg_match_all('#\((.*?)\)#', $str, $period_match);
		$i = 0;
		foreach($period_match[0] as $p){
			if($i>-1){	
				$date_match = "";
				preg_match_all('#[0-9]+#', $p,$date_match);
				
					foreach($date_match[0] as $d){
						array_push($extracted_dates,$d);
					}
				//preg_match_all('#((?![0-9-]).)+#',$p,$qualifiers);
			}
			$i+=1;			
		}
	
	/*date outside of semicolumn */
	}else{
		if(preg_match('#[0-9]+#',$str)&&preg_match('#((?![0-9-]).)+#',$str)){
			
			preg_match_all('#[0-9]+#', $str,$date_match);
			
			/*date with separator ex : 1526/27  */
			if(preg_match('#\/#',$str)){
				$begining = $date_match[0][0];
				$arr = str_split($begining);
				$added_years = $date_match[0][1];
				$end = $arr[0].$arr[1].$added_years;
				
				array_push($extracted_dates,$begining);
				array_push($extracted_dates,$end);
			/*normal date*/
			}else{
				foreach($date_match[0] as $d){
					array_push($extracted_dates,$d);
				}			
				
			}
		}

		
	}
	
	
	$result="";
	
	if(sizeof($extracted_dates)>0){
		$result = '<ul>';	
		foreach( $extracted_dates as $ed){
		
			
			$result.='<li>'.$ed.'</li>';
		
		}	
		$result.='</ul>';				
	}
	
	
	
	//return $result; 
	return $extracted_dates; 
}	


function extract_artist_name($str){

$explode =  explode("(",$str);
$name = $explode[0];


	return $name; 
	
}


function array_to_string_coma_list($array){
	$str = "";
	$separator = ",";
	foreach( $array as $el){
		$str.=strval($el).$separator;
	}
	$remove_last_coma = substr($str, 0, -1);
	return $remove_last_coma;
}

function string_coma_list_to_array($str){
	$str = "";
	$separator = ",";
	$explode = explode($str,$separator );

	return $explode;
}



?>
