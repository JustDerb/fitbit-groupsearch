<?php
include_once '../api/sql_functions.php';

function newItem($row) {
	$result = array();
	$result['url'] = $row['url'];
	$result['name'] = $row['name'];
	$result['members'] = $row['members'];
	$result['description'] = $row['description'];
	$result['esteps'] = number_format($row['steps'],0);
	$result['eactivepoints'] = number_format($row['activepoints'],0);
	$result['edistance'] = number_format($row['distance'],2);
	$result['everyactive'] = number_format($row['veryactive'],0);
	
	return $result;
} 

function getJSONResults($query) {
	global $st_sql;
	$result = mysql_query($query, $st_sql);
	$results = array();
				
	while ($row = mysql_fetch_assoc($result)) {
		$results[] = newItem($row);
	}

	return $results;
}
$results = array();

$limit = (is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 0);
$limitQuery = "";
if ($limit > 0) {
	$limitQuery = " LIMIT 0,".$limit;
}

if ($_GET['f'] == 'members')
{
	$query = "SELECT * FROM `groups` WHERE 1 ORDER BY members DESC ".$limitQuery;
	$results = getJSONResults($query);
} else if ($_GET['f'] == 'steps') {
	$query = "SELECT * FROM `groups` WHERE 1 ORDER BY steps DESC ".$limitQuery;
	$results = getJSONResults($query);
} else if ($_GET['f'] == 'activepoints') {
	$query = "SELECT * FROM `groups` WHERE 1 ORDER BY activepoints DESC ".$limitQuery;
	$results = getJSONResults($query);
} else if ($_GET['f'] == 'distance') {
	$query = "SELECT * FROM `groups` WHERE 1 ORDER BY distance DESC ".$limitQuery;
	$results = getJSONResults($query);
} else if ($_GET['f'] == 'veryactive') {
	$query = "SELECT * FROM `groups` WHERE 1 ORDER BY veryactive DESC ".$limitQuery;
	$results = getJSONResults($query);
}

echo (json_encode($results));
?>