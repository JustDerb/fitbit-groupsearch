<?php
include_once '../api/sql_functions.php';

function newItem($url, $name, $members, $description) {
	$result = array();
	$result['url'] = $url;
	$result['name'] = $name;
	$result['members'] = $members;
	$result['description'] = $description;
	
	return $result;
} 

function getJSONResults($query) {
	global $st_sql;
	$result = mysql_query($query, $st_sql);
	$results = array();
				
	while ($row = mysql_fetch_assoc($result)) {
		$results[] = newItem($row['url'], $row['name'], $row['members'], $row['description']);
	}

	return $results;
}
$results = array();

if ($_GET['f'] == 'members')
{
	$query = "SELECT * FROM `groups` WHERE 1 ORDER BY members DESC";
	$results = getJSONResults($query);
} else if ($_GET['f'] == 'active') {

}

echo (json_encode($results));
?>