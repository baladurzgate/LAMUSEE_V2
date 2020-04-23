
<?php
/* V LOCAL */


include "LAMUSEE_DBconnect.php";


// //************************************************************** MOTHER CLASS

global $lmdb; 







class Lamusee{

	public $shapes;
	public $areas;
	public $paintings;
	public $texts;
	public $artists;
	public $peoples;
	public $dates;
	public $periods;
	public $places;
	public $points;
	public $countries;
	public $regions;
	public $articles;
	
	
	public $LMtables; 
	
	public function __construct() { 
	
		$this->LMobjects = array();
		$this->shapes = array();
		$this->areas = array();
		$this->paintings = array();
		$this->texts = array();
		$this->artists = array();	
		$this->peoples = array();	
		$this->dates = array();	
		$this->periodes = array();	
		$this->countries = array();	
		$this->articles = array();

	
	}
	
	
	
	public function init(){
		
			
		$this->parse_database();
		
		
	}
	
	public function create_tables(){
		
		global $lmdb;
		$lmdb = OpenLamuseeDB();
		
		
		$people = new people("","","");
		$shape = new shape("","","");
		$area = new area("","","","","","");
		$p= array();
		$painting = new painting($p);
		
		
		//$people->add_property("LMID","mediumtext");
		$people->build_table();
		$shape->build_table();
		//$area->build_table();
		//$painting->build_table();
		
		/*$this->parse_old_table($shape);
		$this->parse_old_table($area);
		
		$this->parse_old_table($painting);
		*/
		$this->update_table($shape);
		//$this->update_table($area);
		
		
	}
	
	public function update_table($obj){
		
		global $lmdb;
		$lmdb = OpenLamuseeDB();
		
		$class = get_class($obj);
		
		$class_plurial = $class."s";

		$table_name = "lamusee_".$class_plurial;
		//echo 	$table_name;
		
		foreach ($this->{$class_plurial} as $o){
		
			
			$prop_str = $o->get_properties_string();
			$value_str = $o->get_values_string();
			
			$sql = "INSERT INTO ".$table_name." (".$prop_str.") VALUES (".$value_str.")";
			
			$lmdb->query($sql);
			
			echo $sql;
			
		}

		
	}
	
	public function parse_old_table($obj){
		
		global $lmdb;
		//$lmdb->close();
		$lmdb = OpenOldLamusee();
		
		
		
		$class = get_class($obj);
		
		$class_plurial = $class."s";

		$table_name = "wp_lamusee_".$class_plurial;
		echo 	$class;
		
		switch ($class) {
			case "area" :
			
				foreach( $lmdb->query("SELECT * FROM ".$table_name ) as $params) {
					$this->addObject($class,$params);
				}
				
				break;
			case "shape" :
			
				foreach( $lmdb->query("SELECT * FROM ".$table_name ) as $params) {
					$this->addObject($class,$params);
				}
				
				break;
				
			case 'people':
				
			

				break;
			case 'painting':
			
				/*$params = array();
				$wp_posts = "wp_posts";
				$wp_meta = "wp_postmeta";
			
				foreach( $lmdb->query("SELECT * FROM ".$wp_posts ) as $p) {
				
					/*print_r ($p); 
					$params['id'] = $p['ID'];
					$params['name'] = $p['post_title'];
					
				}*/
		
				break;
		}



		
		$lmdb-> close();

		
	

		
	}
	
	public function generate_serial(){
		
		return rand(00,100)."_".time(d);
	}
	
	
	public function addObject($LMClass,$properties){
		
		$arrayname = $LMClass."s";
		$nObj = new $LMClass($properties);
		
		$objmatch = $this->alreadyExist($nObj);
		
		if ($objmatch==false){
			
			$serialnumber = $this->generate_serial();

			$LMID = "-LMID_".$LMClass."_".$serialnumber."_".sizeof($this->$arrayname)."-";
			$nObj->setLMID($LMID);
			array_push($this->$arrayname,$nObj);	
			
		}else{
			
			
		}
			


	}
	
	public function loadObject($LMClass,$properties){
		
		$arrayname = $LMClass."s";
		$nObj = new $LMClass($properties);
		
		$objmatch = $this->alreadyExist($nObj);
		
		if ($objmatch==false){

			array_push($this->$arrayname,$nObj);	
			
		}else{
			
			
		}
			
	}
	
	
	
	public function alreadyExist($obj){
	
		$keyp = $obj->KeyProperty;
		$LMClass = $obj->LMClass;
		$arrayname = $LMClass."s";
		
		echo $arrayname;
		
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
	
	public function load_table($obj){
		
		
		global $lmdb;
		$lmdb = OpenLamuseeDB();
		
		$class = get_class($obj);
		
		$class_plurial = $class."s";
		
		

		$table_name = "lamusee_".$class_plurial;
		
		echo "loading table ".$table_name;
		
		foreach( $lmdb->query("SELECT * FROM ".$table_name ) as $params) {
			$this->loadObject($class,$params);
		}
				

		$lmdb-> close();
		
		print_r($this->$class_plurial);


		
		
	}

	private function parse_database($db){
		
		
		$this->shapes = array();
		$this->areas = array();
		
		global $lmdb;
		
		
		foreach( $lmdb->get_results("SELECT * FROM wp_lamusee_shapes") as $key => $row) {

			$nshape = new Shape($row->shape_name,$row->shape_nice_name,$row->shape_paintings_list);
			
			array_push($this->shapes,$nshape);			

		}
		
		foreach( $lmdb->get_results("SELECT * FROM wp_lamusee_areas") as $key => $row) {
			
							
			$narea = new Area($row->area_shape_name,$row->area_shape_type,$row->area_nice_name,$row->area_coords,$row->area_painting,$row->area_id);
			
			array_push($this->areas,$narea);
			
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

	public function getShapeByName($n){
		
		foreach ($this->shapes as $shape){
		
			if($shape->getName() == $n){

				return $shape;			
			
			}	
			
		}
		
		return false;
			
	
	}
	
	public function getShapeByID($id){
	
		foreach ($this->shapes as $shape){
		
			if($shape->getID() == $id){

				return $shape;			
			
			}	
			
		}
		
		return false;	
	}
	

	

	

}


class LMproperty
{

    public $name;
	public $type;
	public $value;
	
	
	public function __construct($n,$t){ 
	
		$this->name = $n;
		$this->type = $t;
	
	}
	
}

class LMObject
{

    public $LMID;
	public $properties;
	public $LMClass;
	public $KeyProperty;
	
	
	public function __construct(){ 
	
		echo "new LMObject";
		$this->properties = array();
		$this->LMClass = "LMObject"; 
		$this->add_property("LMID","mediumtext");

		//par default 
		$this->setKeyProperty("LMID");
		
	}
	
	public function setLMID($id){
		
		$this->LMID = $id;
		
	}
	
	//diffère selon la class (ex pour people la property a comparer sera "name" si deux people on le meme name l'un est redondant
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
	
	public function add_property($pname,$ptype){
	
		$p = new LMProperty($pname,$ptype); 
		
		if($this->properties == null){
			$this->properties = array();
		}
		
		if($this->property_exist($pname)==false){
			array_push($this->properties,$p);
			echo '--X--'.$pname;
			echo '----'.$ptype;
		}else{
			echo 'ALREADY EXIST';
		}
		

		
	}
	
	public function get_properties_string(){
	
		$str = "";
		$number_of_p = sizeof($this->properties);
		for($i =0 ; $i < $number_of_p; $i++){
			$p = $this->properties[$i];
			$p_name = $p->name;
			$coma = ", ";
			if($i == $number_of_p-1){
				$coma = " ";
			}
			$str.=$p_name.$coma;
			
		}
		
		return $str;
		
	}
	
	public function set_property_value($pn,$v){
		$p = $this->properties[$pn];
	}
	
	public function get_values_string(){
	
		$str = "'";
		$number_of_p = sizeof($this->properties);
		for($i =0 ; $i < $number_of_p; $i++){
			$p = $this->properties[$i];
			$p_name = $p->name;
			$v= $this->{$p_name};
			$coma = "', '";
			if($i == $number_of_p-1){
				$coma = "'";
			}
			$str.=$v.$coma;
			
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
			
			for ($i = 0; $i<sizeof($this->properties);$i++){
					
				$p = $this->properties[$i];
				
				echo "____".$p->name."_____";
				
				$sql.="`".$p->name."` ".$p->type." NOT NULL,";
			
			}
			$sql.="UNIQUE KEY id (id));";

			
			//print_r($lmdb->query($sql));
			$lmdb->query($sql);
			
		
		}
	}	
	
	
	
	
}


class LMName extends LMObject
{
	
	

}


//  //************************************************************** TIME CLASSES


class LMDate extends LMObject
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
	
	}
	
	
}




////**************************************************************  SPACE CLASSES

class point extends LMObject
{
	private $x;
	private $y;
	
	public function __construct($x,$y){ 
	
		$this->x = $x;
		$this->y = $y;
	
	}

}

class Place extends LMObject
{
	private $name;
	private $point;
	
	public function __construct($name,$point){ 
	
		$this->name = $name;
		$this->point = $point;
	
	}
}


class Region extends LMObject
{

}

class Country extends LMObject
{

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
		
				
			
				if(gettype ( $param )== "array"){
					
					foreach($param as $key => $row){
						
						$class = get_class($this);
					
						if(property_exists ($class,$key)) {
						
							$this->$key = $row;
							$this->add_property($key,"mediumtext");
					
						}
					
					}			
					
				}

				

	
		/*
	
		$this->name = $name;
		//period
		$this->period = $period;
		//place
		$this->place_of_birth = $place_of_birth;*/
		
		$this->setKeyProperty('name');
		
		$this->LMClass= "people";
		$this->add_property("name","mediumtext");
		$this->add_property("period","mediumtext");
		$this->add_property("place_of_birth","mediumtext");
		$this->add_property("country","mediumtext");
		$this->add_property("profession","mediumtext");
		$this->add_property("work_place","mediumtext");
		$this->add_property("biography","mediumtext");
		
	}
	
	/*public function old_build_table() {
		
		global $lmdb;
  		global $table_name ;
  		
  		$table_name = "lamusee_peoples";

		if($lmdb->query("DESCRIBE '$table_name'") == FALSE) 
		{
			$sql = "CREATE TABLE " . $table_name . " (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`LMID` mediumtext NOT NULL,
			`name` mediumtext NOT NULL,
			`period` mediumtext NOT NULL,
			`place_of_birth` mediumtext NOT NULL,
			`country` mediumtext NOT NULL,
			`profession` mediumtext NOT NULL,
			`work_place` mediumtext NOT NULL,
			`biography` mediumtext NOT NULL,
			UNIQUE KEY id (id)
			);";
 
			$lmdb->query($sql);
		}
	}*/
}

//Painting
class painting  extends LMObject
{
	public  $id; 
	public  $name;
	public  $nice_name;
	public  $lowres_image;
	public  $areas;
	public  $linked_text;
	public  $map_scale;
	public  $map_offset_x;
	public  $map_offset_y;
	public  $image_highdef;
	public  $artiste;
	public  $titre_du_tableau;
	public  $technique;
	public  $date;
	public  $dimensions;
	public  $lieu_de_conservation;
	public  $pays;
	public  $region;
	public  $artiste2;



		public function __construct($param) { 
		
				parent::__construct();
		
				$this->setKeyProperty('name');
				
				echo "NEW PAINTING";
			
				if(gettype ( $param )== "array"){
					
					foreach($param as $key => $row){
						
						$class = get_class($this);
					
						if(property_exists ($class,$key)) {
						
							$this->$key = $row;
					
						}
					
					}			
					
				}		

		$this->LMClass= "painting";
		
		$this->add_property("name","mediumtext");
		$this->add_property("nice_name","mediumtext");
		$this->add_property("areas","mediumtext");
		$this->add_property("linked_text","mediumtext");
		$this->add_property("map_scale","mediumtext");
		$this->add_property("map_offset_x","mediumtext");
		$this->add_property("map_offset_y","mediumtext");
		$this->add_property("image_highdef","mediumtext");
		$this->add_property("artiste","mediumtext");
		$this->add_property("technique","mediumtext");
		$this->add_property("date","mediumtext");
		$this->add_property("dimensions","mediumtext");
		$this->add_property("lieu_de_conservation","mediumtext");
		$this->add_property("pays","mediumtext");
		$this->add_property("region","mediumtext");
		$this->add_property("artiste2","mediumtext");

		
	}

}


// Extract
class LM_Extract extends LMObject

{

}

// BOOK
class Book extends LMObject

{

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
				
					$this->$key = $row;
			
				}
			
			}			
			
		}
	/*public function __construct($name,$nice_name,$paintings_list) { 
	
	/*
		$this->shape_name = $name;
		$this->shape_nice_name = $nice_name;
		$this->shape_paintings_list = $paintings_list;
		*/
		$this->LMClass = "shape";
	
		$this->add_property("shape_name","mediumtext");
		$this->add_property("shape_nice_name","mediumtext");
		$this->add_property("shape_paintings_list","mediumtext");
		
	
	}
	
	public function add_painting($p){
	
			
		
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
	
	public function old_build_table() {
		
		global $lmdb;
  		global $table_name ;
  		
  		$table_name = "lamusee_shapes";

		if($lmdb->query("DESCRIBE '$table_name'") == FALSE) 
		{
			$sql = "CREATE TABLE " . $table_name . " (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`shape_name` mediumtext NOT NULL,
			`shape_nice_name` mediumtext NOT NULL,
			`shape_paintings_list` mediumtext NOT NULL,
			`shape_creation_date` mediumtext NOT NULL,
			`shape_last_modification` mediumtext NOT NULL,
			`shape_clicks` mediumtext NOT NULL,
			UNIQUE KEY id (id)
			);";
 
			$lmdb->query($sql);
		}
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
				
					$this->$key = $row;
			
				}
			
			}			
			
		}	
	
	/*public function __construct($sn,$st,$nn,$c,$p,$id) { 
	
	
			$this->LMCLass = "area";
			$this->area_shape_name = $sn;
			$this->area_shape_type = $st;
			$this->area_nice_name = $nn;
			$this->area_coords = $c;
			$this->area_painting = $p;	
			$this->area_id = $id;
			*/
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
	
	public function old_build_table() {
		
		global $lmdb;
  		global $table_name ;
  		
  		$table_name = "lamusee_areas";

		if($lmdb->query("DESCRIBE '$table_name'") == FALSE) 
		{
			$sql = "CREATE TABLE " . $table_name . " (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`area_shape_name` mediumtext NOT NULL,
			`area_shape_type` mediumtext NOT NULL,
			`area_nice_name` mediumtext NOT NULL,
			`area_coords` mediumtext NOT NULL,
			`area_painting` int NOT NULL,
			`area_id` mediumtext NOT NULL,
			UNIQUE KEY id (id)
			);";
			$lmdb->query($sql);
		}
		
		
	}
	
	public function transfer_from_old(){
	
		
		
	}

}

//************************************************************** BIG CLASSES



class Painting_image extends LMObject
{

}


class LM_article extends LMObject
{
	
}


//-------------------------------------------------------------------------------
//-------------------------------------------------------------------------------
//------------------------mise a jour de la DB----------------------------------------
//-------------------------------------------------------------------------------


if(!function_exists('get_shape_list')){
	
/* Parcours tout les tableaux du site et inspecte leur shapes pour ensuite ajouter l'index du tableau(post_ID) dans les différentes lignes de l'array shape_list.
voir rajouter une nouvelle ligne si le tableau contient une shape inexistante dans le tableau. 

 return array([name(string)][paintings(array)])
 
 Exemple : [0]["ange"][8,20,11]
 			  [1]["casque"][12,154,20,9]*/	

	function get_shape_list(){
		
		$shape_list = array();

		$query = array( 'post_status' => 'publish','numberposts' => -1 );

		$all_published_posts = get_posts($query);
		
		foreach ( $all_published_posts as $post ) {
			
			$post_areas_str = get_field('areas',$post->ID);
			
			$post_shapes = collect_shapes($post_areas_str);
			
			$added_shapes = array();
			
			$treated_shapes = array();
			
			foreach ( $post_shapes as  $shape ) {
				
				$match1 = 0;
				
				if(count($shape_list)>0){
					
					foreach ( $shape_list as $key => $from_list ) {
						
						if($shape == $from_list['name'] && $shape != "" && !isset($treated_shapes[$shape]) ){
							
							array_push($shape_list[$key]['paintings'],$post->ID);
							
							$match1++;
							
							$treated_shapes[$shape] = true;

						}
				
					}
				
				}
				
				if($match1 == 0 && $shape != "" && !isset($treated_shapes[$shape])){
					
					$row = array();
					
					$row['name']= $shape;
					
					$row['paintings'] = array();
						
					array_push($row['paintings'],$post->ID);
						
					array_push($added_shapes,$row);
					
					$treated_shapes[$shape] = true;
					
				}
				
			}
				
			$merged_shape_list = array_merge($shape_list,$added_shapes);
			
			$shape_list = $merged_shape_list ;
				
		}
		
		return $shape_list;

	}

}


if(!function_exists('build_shapes_table')){
	
	
	function build_shapes_table(){

		global $lmdb;
  		global $table_name ;
  		
  		$table_name = $lmdb->prefix."lamusee_shapes";
 
		// on creer la table "wp_lamusee_shapes" qui renseigne sur le nom des shapes , l'index des tableaux(post_ID) où elles apparaissent et 
		/*
			shape_name                :nom de la shape 
			shape_nice-name           :nom affiché
			shape_creation_date       :date de creation de la shape
			shape_last_modification   :date de la dernière modification de la shape (ajout de tableau)
			shape_paintings_list      :liste des indexes des tableaux(post_ID) où la shape est présente
			shape_clicks				  :total des clicks sur la shape. 

			
		
		
		
		*/
		
		
		if($lmdb->get_var("show tables like '$table_name'") != $table_name) 
		{
			$sql = "CREATE TABLE " . $table_name . " (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`shape_name` mediumtext NOT NULL,
			`shape_nice_name` mediumtext NOT NULL,
			`shape_creation_date` int NOT NULL,
			`shape_last_modification` int NOT NULL,
			`shape_paintings_list` mediumtext NOT NULL,
			`shape_clicks` mediumint(9) NOT NULL,
			UNIQUE KEY id (id)
			);";
 
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);	
			
			
			$shape_list = get_shape_list();
		
				if(count($shape_list)>0){
					
					foreach ( $shape_list as $key => $from_list ) {
						
						// on convertis la listes des tableaux associés à la shape en string de forme a,b,c,d
						
						$serialized_paintings_list = substr(implode(", ", $from_list['paintings']), 0);
						
						// on rempli la table wp_lamusee_shapes dans la base de donnée

						$lmdb->insert($table_name,
    	 						array(
          						'shape_name'=>$from_list['name'],
          						'shape_nice_name'=>$from_list['name'],
          						'shape_creation_date'=>time(),
          						'shape_last_modification'=>time(),
          						'shape_paintings_list'=> $serialized_paintings_list,
          						'shape_clicks'=>0
     							),
    	 						array( 
          						'%s',
          						'%s',
          						'%d',
          						'%d',
          						'%s',
          						'%d'
     							)
						);

					}
				
				}
				
			
		
		}
		

		
		
		
		
	}
	
	/*build_shapes_table();
		$results = $lmdb->get_results("SELECT * FROM wp_lamusee_shapes");
		print_r($results);*/
		

}

if(!function_exists('transfer_areas_table')){
	
	
	function transfer_areas_table(){

		global $lmdb;
  		global $table_name ;
  		
  		$table_name = $lmdb->prefix."lamusee_areas";
 
		// on creer la table "wp_lamusee_areas" 
		/*
			area_shape                :shape associée
			area_points               :points
	
		
		
		*/
		
		
		if($lmdb->get_var("show tables like '$table_name'") != $table_name) 
		{
			$sql = "CREATE TABLE " . $table_name . " (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`area_shape_name` mediumtext NOT NULL,
			`area_shape_type` mediumtext NOT NULL,
			`area_nice_name` mediumtext NOT NULL,
			`area_coords` mediumtext NOT NULL,
			`area_painting` int NOT NULL,
			`area_id` mediumtext NOT NULL,
			UNIQUE KEY id (id)
			);";
 
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);	
			
			$areas_list = get_areas_list();
			
			if(count($areas_list)>0){
				
				foreach ( $areas_list as $key => $area ) {	
			
						$lmdb->insert($table_name,
    	 						array(
          						'area_shape_name'=>$area->getShapeName(),
          						'area_shape_type'=>$area->getShapeType(),
          						'area_nice_name'=>$area->getNiceName(),
          						'area_coords'=>$area->getCoords(),
          						'area_painting'=>$area->getPainting(),
          						'area_id'=>$area->getID(),
     							),
    	 						array( 
          						'%s',
          						'%s',
          						'%s',
          						'%s',
          						'%s',
          						'%s'
     							)
						);					
			
				}
				
			}
		
		}
		

		
		
		
		$results = $lmdb->get_results("SELECT * FROM wp_lamusee_areas");
		//print_r($results);
		
		
	}
	
	
	//build_areas_table();


}


/*    UTILS ________ */



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
	
	if(sizeof(extracted_dates>0)){
		$result = '<ul>';	
		foreach( $extracted_dates as $ed){
		
			
			$result.='<li>'.$ed.'</li>';
		
		}	
		$result.='</ul>';				
	}
	
	
	
	//return $result; 
	return $extracted_dates; 
}	


?>
