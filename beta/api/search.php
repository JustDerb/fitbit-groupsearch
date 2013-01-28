<?php
require_once('sqlSearch.php');
require_once('sql_functions.php');

function clean($elem) 
{ 
    if(!is_array($elem)) 
        $elem = htmlspecialchars($elem); 
    else 
        foreach ($elem as $key => $value) 
            $elem[$key] = $this->clean($value); 
    return $elem; 
} 


function is_int2($v) {
  $i = intval($v);
  if ("$i" == "$v") {
    return TRUE;
  } else {
    return FALSE;
  }
}

function getColorHTML($value, $min, $max) {
	$value_fix = $value - $min;
	$max_fix = $max - $min;
	$ratio = $value_fix/$max_fix;
	
	$badge = "";
	
	if ($ratio <= 0.33)
		$badge = "badge-important";
	else if ($ratio <= 0.66)
		$badge = "badge-warning";
	else
		$badge = "badge-success";
		
	return '<span class="badge '.$badge.'">'.$value.'</span>';
}


class fitbitSearch {
	private $total = -1;

	public function search($searchTerms, $pageNum=0,$numOfItems=24) {
		global $st_sql;
	
		$startGroup = $pageNum*$numOfItems;
		
		$baseQuery = search_keywordMatch($searchTerms);
		$query =  "select id, name, description, members, url, steps, activepoints, distance, veryactive ".$baseQuery;
		
		$totalItemsQ = "SELECT COUNT( * ) AS total from ( \n".$query." ) as tt";
		$result = mysql_query($totalItemsQ, $st_sql);
		$row = mysql_fetch_assoc($result);
		$this->total = $row['total'];
		
		$query .= " LIMIT ".$startGroup." , ".$numOfItems;
		
		$result = mysql_query($query, $st_sql);
		
		$retResults = array();
		while ($row = mysql_fetch_assoc($result)) {
			$retResults[] = array("members" => $row['members'],
									"title" => $row['name'],
									"description" => $row['description'],
									"steps" => $row['steps'],
									"activepoints" => $row['activepoints'],
									"distance" => $row['distance'],
									"veryactive" => $row['veryactive']
									);
		}
		
		return $retResults;
	}
	
	public function getTotal() {
		return $this->total;
	}
}
?>