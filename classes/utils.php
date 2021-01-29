<?php
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


function extract_date($str,$output_array=false){

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