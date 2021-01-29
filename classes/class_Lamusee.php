
<?php
/* V LOCAL */

// //************************************************************** MOTHER CLASS

/*
realy need to cut this classes in separate files

// and hashing the feilds 

store properties un database
*/


class Lamusee{

	public $shapes;
	public $areas;
	public $paintings;
	public $pictures;
	public $texts;
	public $books;
	public $peoples;
	public $dates;
	public $places;
	public $regions;
	public $LMobjects;
	public $periods;
	public $messagelogs;


	
	public $LMtables; 
	
	public function __construct() { 
	
		$this->LMobjects = array();
		$this->periods = array();
		$this->shapes = array();
		$this->areas = array();
		$this->paintings = array();
		$this->pictures = array();
		$this->texts = array();
		$this->peoples = array();	
		$this->dates = array();	
		$this->places = array();	
		$this->regions = array();	
		$this->books = array();
		$this->messagelogs = array();

	
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
		$period = new period(array());		
		
		$this->load_table($people);
		$this->load_table($shape);
		$this->load_table($area);
		$this->load_table($place);
		$this->load_table($region);
		$this->load_table($painting);
		$this->load_table($picture);
		$this->load_table($text);
		$this->load_table($book);
		$this->load_table($period);
	
		
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
		$period = new period(array());	

		$this->build_table($picture);
		$this->build_table($people);
		$this->build_table($shape);
		$this->build_table($area);
		$this->build_table($place);
		$this->build_table($region);
		$this->build_table($painting);
		$this->build_table($book);
		$this->build_table($text);
		$this->build_table($period);

	}
	
	
	public function build_table($obj) {
		
		global $lmdb;
		
		if($lmdb == null){
			
			$lmdb = OpenLamuseeDB();
		}
		
  		$table_name = "lamusee_".$obj->LMClass."s";

		if($lmdb->query("DESCRIBE '$table_name'") == FALSE) 
		{
			
			$sql = "CREATE TABLE " . $table_name . " (`id` mediumint(9) NOT NULL AUTO_INCREMENT,";
			
			foreach ($obj->properties as $p){
				
				echo "____".$p->name."_____";
				
				$sql.="`".$p->name."` ".$p->type." NOT NULL,";
			
			}
			
			$sql.="UNIQUE KEY id (id));";
			
			echo "<br>";
			print_r($sql);
			echo "<br>";

			if($lmdb->query($sql)!=false){
					
					$log = "new table added to DB ".$table_name;
								
					
			}else{
				
				$log = "QUERY ".$sql."  FAILED !";
			}
				
			$this->add_log($log);
			
		}
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
		$period = new period(array());	
		
		$this->update_table($area);
		$this->update_table($shape);
		$this->update_table($people);
		$this->update_table($place);
		$this->update_table($region);
		$this->update_table($painting);
		$this->update_table($picture);
		$this->update_table($book);
		$this->update_table($text);
		$this->update_table($period);
		
		
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
		$period = new period(array());
		
		$this->display_lmobj_list($area);
		$this->display_lmobj_list($shape);
		$this->display_lmobj_list($people);
		$this->display_lmobj_list($place);
		$this->display_lmobj_list($region);
		$this->display_lmobj_list($picture);
		$this->display_lmobj_list($painting);
		$this->display_lmobj_list($text);
		$this->display_lmobj_list($book);
		$this->display_lmobj_list($period);



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
		
					$this->add_or_update_LMobject_in_db($o);

		}

	}
	
	public function add_or_update_LMobject_in_db($o){
		
		global $lmdb;
		
		//$lmdb = OpenLamuseeDB();
		
		$class = get_class($o);
		
		$class_plurial = $class."s";

		$table_name = "lamusee_".$class_plurial;
		
		// check if the object is already stored in the db by its  LMID

		$sql = "SELECT * FROM ".$table_name." WHERE LMID = '".$o->LMID."'";
		
		$result = $lmdb->query($sql);

		
		if ($result->num_rows > 0) {
				
				// if the lmobjects exist in the db 

				  while($row = $result->fetch_assoc()) {
					  
					 echo "<br>-------EXIST IN DB------<br>";
					 
					 // compare properties 
					 
					$SET_string ="";
					
					$Old_values = "";
					
					$dontmatch = 0;
					
					$db_params = $row;
					
					//convert db datas to proper types (json to array ect...)
					$loaded_lmobject = $this->preloadObject($class,$db_params);
					 
					foreach ($loaded_lmobject->properties as $p){
						
						$p_name = $p->name;			 

						 $separator = " ";
	
						 // we compare every properties values except LMtimestamp because the LMtimestamps will always be different
						 if($o->$p_name != $loaded_lmobject->$p_name && $p_name != "LMtimestamp"){
							 
							if($dontmatch > 0 ){
								$separator = " , ";
							}
							 
							$SET_string.=$separator.$o->get_property_sql_SET_string($p_name); //generate a string in form of "property = value";
							
							$Old_values.=$separator.$loaded_lmobject->get_property_sql_SET_string($p_name);
							
							$dontmatch++;
							
						 }

					 }
					 
					if($dontmatch>0){

						// we update the DB entry
						
						//we then update the LMtimestamp 
						$SET_string.=", LMtimestamp = '".$o->LMtimestamp."'";
					
						$sql ="UPDATE ".$table_name." SET ".$SET_string." WHERE LMID = '".$o->LMID."'";
						
						echo '<br> number of properties to update : ';
						print_r($dontmatch);
						echo '<br>';
						echo '<br> UPDATE SQL :::::';
						print_r($sql);
						echo '<br>';	
						
						if($lmdb->query($sql)!=false){
							
							$log = "LMObject updated in db :".$o->LMID." ".$o->getKeyPropertyValue()." old values (".$Old_values.") new values (".$SET_string.")";
							
						}else{
							
							$log = "QUERY ".$sql."  FAILED !";
						}
						
						$this->add_log($log);
						
					}else{
						
						echo '<br> no properties to update ';
						
					}

				}

		}else {
				
				// we create the entry for this object
				
				$prop_str = $o->get_properties_string();
				
				$value_str = $o->get_values_string();
				
				$sql = "INSERT INTO ".$table_name." (".$prop_str.") VALUES (".$value_str.")";
				
				echo '<br>';
				print_r($sql);
				echo '<br>';
				
				if($lmdb->query($sql)!=false){
					
						$log = "new LMObject added to db :".$o->LMID." ".$o->getKeyPropertyValue();
					
				}else{
							
						$log = "QUERY ".$sql."  FAILED !";
				}
						
				$this->add_log($log);

				
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
		
		$this->add_log($sql);
		
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

	
	
	public function addObject($LMClass,$properties,$forcedid=""){
		
		// warning this function does not check for duplicates before pushing
			
		$arrayname = $LMClass."s";
		
		$nObj = new $LMClass($properties);

		$keyp = $nObj->KeyProperty;
		
		$key_value= $nObj->$keyp;
		
		$LMID = $this->generate_serial($nObj);
		
		$nObj->LMtimestamp = time();
		
		if($forcedid==""){
			$nObj->setLMID($LMID);
		}else{
			$nObj->setLMID($forcedid);
		}
		
		
		
		array_push($this->$arrayname,$nObj);	
		array_push($this->LMobjects,$nObj);	
			
		return $nObj;

	}
	
	public function loadObject($LMClass,$properties){
		
		// warning this function does not check for duplicates before pushing
		
		$arrayname = $LMClass."s";
		$nObj = new $LMClass($properties);
		
		array_push($this->$arrayname,$nObj);	
			
	}
	
	public function preloadObject($LMClass,$properties){
		
		$arrayname = $LMClass."s";
		//the constructor of the LMclass eventualy decode json arrays 
		$nObj = new $LMClass($properties);
		
		return 	$nObj;
			
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
	
	// LOG
	
	
	public function add_log($str){
		
		array_push($this->messagelogs,$str);
	}
	
	public function get_log_txt(){
		
		$str = "";
		
		foreach($this->messagelogs as $log){
			
			$str.="\n".$log;
		}
		
		
		return $str;
	}
	
	public function get_log_html(){
		
		$str = "";
		
		foreach($this->messagelogs as $log){
			
			$str.="<br>".$log;
		}
		
		
		return $str;		

	}
	
	public function save_log(){
		
		$myfile = fopen("lamuseev2_log.html", "w");
		
		$file = "lamuseev2_log.html";
		// Ouvre un fichier pour lire un contenu existant
		$current = file_get_contents($file);
		// Ajoute une personne

		// Écrit le résultat dans le fichier
		file_put_contents($file, $this->messagelogs, FILE_APPEND);		

	}
	public function get_link($o){
		
		
		$link ="";
		
		if(gettype($o) == "object"){
		
			$id = $o->LMID;
			
			$view_page = "view_object.php";
		
			$link = 'http://localhost/LAMUSEE_V2/'.$view_page.'?id='.$id;
			
		}
			
		return $link;
		
		


	}
	
	public function get_html_link($o){
		
		$html = "";
	
		if(gettype($o) == "object"){
	
			$link = $this->get_link($o);
			
			$key_value = $o->getKeyPropertyValue();
		
			$html = '<a href="'.$link .'">'.$key_value.'</a>';

						
		}
		
		return $html;	



	}
	
	
	public function get_table_size(){
		
		
	}
	
	//THE MOST IMPORTANT METHODS ! 
	
	public function generate_serial($obj,$increment=0){
		
		
		$class = get_class($obj);

		$arrayname = $class."s";
		
		$keyp = $obj->KeyProperty;
		
		$key_value= $obj->$keyp;
		
		$number = uniqid();
		
		//$number = sizeof($this->$arrayname)+$increment;  // RISKY !!! what if the count is not chronological ? 
		
		//$serial = "LM".$class.sizeof($this->$arrayname)."-".strlen($key_value).rand(000,100);
		
		$serial = "LM".$class."-".$number;

		return $serial;
		
	}	
	

	public function find_lmobject($LMID){
		
		if(gettype($LMID) == "string"){
		
			$explode = explode("-",$LMID,);
			$class_plurial = substr($explode[0],2)."s";
			
			//temporary not the best method
			//would be better to make an 2d array  with id and objects 
			
			foreach($this->$class_plurial as $o){
				if($o->LMID == $LMID){
					return $o;
					
				}
			}

		
		}
				
		return false;
		
	}
}




?>
