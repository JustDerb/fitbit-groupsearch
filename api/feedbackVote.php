<?php
require_once('sql_functions.php');

function insertVote($id,$ip,$vote) {
	global $st_sql;
	
	$id = st_mysql_encode($id, $st_sql);
	$ip = st_mysql_encode($ip, $st_sql);
	
$query = <<<FBVQUERY
INSERT INTO feedbackvotes (`id`, `feedbackid`, `value`, `ip`) VALUES (NULL, '{$id}', '{$vote}', '{$ip}')
ON DUPLICATE KEY UPDATE `value`='{$vote}'
FBVQUERY;
	
	return mysql_query($query, $st_sql);

}

if (isset($_GET['id'])) {
	$vote = 0;
	if (isset($_GET['v'])) {
		if (strcasecmp($_GET['v'], "u") == 0)
		{
			$vote = 1;
		}
		else if (strcasecmp($_GET['v'], "d") == 0)
		{
			$vote = -1;
		}
	}
	
	$result = insertVote($_GET['id'],$_SERVER['REMOTE_ADDR'],$vote);

	if ($result) {
		http_response_code(200);
		exit();
	}
	else
	{
		http_response_code(400);
		exit();
	}
}

?>