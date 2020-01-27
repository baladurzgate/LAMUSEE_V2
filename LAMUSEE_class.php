<?php
include "LAMUSEE_DBconnect.php";


// //************************************************************** MOTHER CLASS

global $wpdb; 







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
	public $articules;
	
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
		
		global $wpdb;
		$wpdb = OpenLamuseeDB();

		$people = new people("","","");
		$shape = new shape("","","");
		$area = new area("","","","","","");
		
		$people->build_table();
		$shape->build_table();
		$area->build_table();
		
		$this->parse_old_table($shape);
		$this->parse_old_table($area);
		
		
	}
	
	public function update_table($obj){
		
		global $wpdb;
		$wpdb = OpenLamuseeDB();
		
		$class = get_class($obj);
		
		$class_plurial = $class."s";

		$table_name = "lamusee_".$class_plurial;
		echo 	$table_name;
		
		foreach ($this->{$class_plurial} as $obj){
		
			
			$properties = $obj->get_property_string();
			$properties = $obj->get_values_string();
			
			$query = "INSERT INTO Customers (".$properties_str.")VALUES (".$values.")";
			
		}

		
	}
	
	
	public function parse_old_table($obj){
		
		global $wpdb;
		$wpdb-> close();
		$wpdb = OpenOldLamusee();
		
		$class = get_class($obj);
		
		$class_plurial = $class."s";

		$table_name = "wp_lamusee_".$class_plurial;
		echo 	$table_name;

		foreach( $wpdb->query("SELECT * FROM ".$table_name ) as $params) {
			
			$this->addObject($class,$params);

		}
		
	

		
	}
	
	
	public function addObject($LMClass,$properties){
		
			$arrayname = $LMClass."s";
			$nObj = new $LMClass($properties);
			array_push($this->$arrayname,$nObj);	

	}

	private function parse_database($db){
		
		
		$this->shapes = array();
		$this->areas = array();
		
		global $wpdb;
		
		foreach( $wpdb->get_results("SELECT * FROM wp_lamusee_shapes") as $key => $row) {

			$nshape = new Shape($row->shape_name,$row->shape_nice_name,$row->shape_paintings_list);
			
			array_push($this->shapes,$nshape);			

		}
		
		foreach( $wpdb->get_results("SELECT * FROM wp_lamusee_areas") as $key => $row) {
			
							
			$narea = new Area($row->area_shape_name,$row->area_shape_type,$row->area_nice_name,$row->area_coords,$row->area_painting,$row->area_id);
			
			array_push($this->areas,$narea);
			
		}
		
		foreach( $wpdb->get_results("SELECT * FROM wp_lamusee_areas") as $key => $row) {
			
							
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
		
		echo $html;
		
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
	
	public function add_object(){
	
		foreach ($this->shapes as $shape){
		
			if($shape->getID() == $id){

				return $shape;			
			
			}	
			
		}
		
		return false;	
	}

	public function add_LMObject(){
	
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

    public $ID;
	public $properties;
	public $LMClass;
	
	
	public function __construct(){ 
	
		echo "new LMObject";
		//generate unique ID and check DB (WIP)
		$this->ID = 0;
		//properties is aimed at dialoguing with different types of databases. 
		$this->properties = array();
		$this->$LMClass = "LMObject"; 

	}
	public function add_property($pname,$ptype){
	
		$p = new LMProperty($pname,$ptype); 
		
		if($this->properties == null){
			$this->properties = array();
		}
		
		array_push($this->properties,$p);
		
	}
	
	public function get_properties_string(){
	
		$str = "";
		$number_of_p = sizeof($this->properties);
		for($i =0 ; $i < $number_of_p; $i++){
			$p = $this->properties[$i];
			$coma = ", ";
			if($i < $number_of_p-1){
				$coma = "";
			}
			$str.=$p.$coma;
			
		}
		
		return $str;
		
	}
	
	public function get_values_string(){
	
		$str = "'";
		$number_of_p = sizeof($this->properties);
		for($i =0 ; $i < $number_of_p; $i++){
			$v= $this->{properties[$i]};
			$coma = "', '";
			if($i < $number_of_p-1){
				$coma = "'";
			}
			$str.=$v.$coma;
			
		}
		
		return $str;
		
	}
		
	
	public function build_table() {
		
		global $wpdb;
  		$table_name ;
		
  		$table_name = "lamusee_".$this->LMClass+"s";

		if($wpdb->query("DESCRIBE '$table_name'") == FALSE) 
		{
			
			$sql = "CREATE TABLE " . $table_name . " (`id` mediumint(9) NOT NULL AUTO_INCREMENT,";
			
			for ($i = 0; $i<sizeof($this->properties);$i++){
					
				$p = $this->properties[$i];
				$sql.="`".$p->name."` ".$p->type." NOT NULL,";
			
			}
			$sql.="UNIQUE KEY id (id));";
			
			echo $sql;
			
			$wpdb->query($sql);
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
		$this->comment = comment;
	
	}
}



class period extends LMObject
{

	private $name;
	private $begining_date;
	private $end_date;
	private $format;
	
	public function __construct($name,$begining_date,$end_date,$format){ 
	
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
	private $name;
	private $period;
	private $place_of_birth;
	private $country;
	private $profession;
	private $work_place;
	private $biography;

		public function __construct($param) { 
			
				if(gettype ( $param )== "array"){
					
					foreach($param as $key => $row){
						
						$class = get_class($this);
					
						if(property_exists ($class,$key)) {
						
							$this->$key = $row;
					
						}
					
					}			
					
				}		

	
		/*$this->LMCLass = "people";
	
		$this->name = $name;
		//period
		$this->period = $period;
		//place
		$this->place_of_birth = $place_of_birth;*/
		
		$this->LMCLass= "people";
		
		$this->add_property("name","mediumtext");
		$this->add_property("period","mediumtext");
		$this->add_property("place_of_birth","mediumtext");
		$this->add_property("country","mediumtext");
		$this->add_property("profession","mediumtext");
		$this->add_property("work_place","mediumtext");
		$this->add_property("biography","mediumtext");
		
	
	
	}
	
	public function old_build_table() {
		
		global $wpdb;
  		global $table_name ;
  		
  		$table_name = "lamusee_peoples";

		if($wpdb->query("DESCRIBE '$table_name'") == FALSE) 
		{
			$sql = "CREATE TABLE " . $table_name . " (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`name` mediumtext NOT NULL,
			`period` mediumtext NOT NULL,
			`place_of_birth` mediumtext NOT NULL,
			`country` mediumtext NOT NULL,
			`profession` mediumtext NOT NULL,
			`work_place` mediumtext NOT NULL,
			`biography` mediumtext NOT NULL,
			UNIQUE KEY id (id)
			);";
 
			$wpdb->query($sql);
		}
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


	private $shape_ID;
	private $shape_name;
	private $shape_creation_date;
	private $shape_last_modification;
	private $shape_nice_name;
	private $shape_paintings_list;
	private $shape_clicks;

	public function __construct($param) { 
	
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
		$this->LMCLass = "shape";
	
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
		
		global $wpdb;
  		global $table_name ;
  		
  		$table_name = "lamusee_shapes";

		if($wpdb->query("DESCRIBE '$table_name'") == FALSE) 
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
 
			$wpdb->query($sql);
		}
	}

}


class area extends LMObject{
	
	private $area_shape_name;
	private $area_shape_type;
	private $area_nice_name;
	private $area_coords;
	private $area_painting;
	private $area_id;

	
	public function __construct($param) { 
			
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
		
		global $wpdb;
  		global $table_name ;
  		
  		$table_name = "lamusee_areas";

		if($wpdb->query("DESCRIBE '$table_name'") == FALSE) 
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
			$wpdb->query($sql);
		}
		
		
	}
	
	public function transfer_from_old(){
	
		
		
	}

}

//************************************************************** BIG CLASSES

class Painting extends LMObject
{

}

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

		global $wpdb;
  		global $table_name ;
  		
  		$table_name = $wpdb->prefix."lamusee_shapes";
 
		// on creer la table "wp_lamusee_shapes" qui renseigne sur le nom des shapes , l'index des tableaux(post_ID) où elles apparaissent et 
		/*
			shape_name                :nom de la shape 
			shape_nice-name           :nom affiché
			shape_creation_date       :date de creation de la shape
			shape_last_modification   :date de la dernière modification de la shape (ajout de tableau)
			shape_paintings_list      :liste des indexes des tableaux(post_ID) où la shape est présente
			shape_clicks				  :total des clicks sur la shape. 

			
		
		
		
		*/
		
		
		if($wpdb->get_var("show tables like '$table_name'") != $table_name) 
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

						$wpdb->insert($table_name,
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
		$results = $wpdb->get_results("SELECT * FROM wp_lamusee_shapes");
		print_r($results);*/
		

}

if(!function_exists('transfer_areas_table')){
	
	
	function transfer_areas_table(){

		global $wpdb;
  		global $table_name ;
  		
  		$table_name = $wpdb->prefix."lamusee_areas";
 
		// on creer la table "wp_lamusee_areas" 
		/*
			area_shape                :shape associée
			area_points               :points
	
		
		
		*/
		
		
		if($wpdb->get_var("show tables like '$table_name'") != $table_name) 
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
			
						$wpdb->insert($table_name,
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
		

		
		
		
		$results = $wpdb->get_results("SELECT * FROM wp_lamusee_areas");
		print_r($results);
		
		
	}
	
	
	//build_areas_table();


}







?>
