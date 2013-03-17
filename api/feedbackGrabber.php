<?php
require_once('sql_functions.php');

class feedbackGrabber {
	public function grabAll($pointsDesc=true) {
		if ($pointsDesc)
			$pointsOrdering = "DESC";
		else
			$pointsOrdering = "ASC";
$query = <<<FBQUERY
		SELECT F.*, COALESCE(SUM(V.value),0) as "points"
		FROM  `feedback` F LEFT JOIN `feedbackvotes` V
		ON F.id = V.feedbackid
		GROUP BY F.id
		ORDER BY COALESCE(SUM(V.value),0) {$pointsOrdering}
FBQUERY;

		global $st_sql;
		$result = mysql_query($query, $st_sql);
		
		$retArray = array();
		if ($result) {
			while ($row = mysql_fetch_array($result)) {
				if (strcasecmp($row['private'], "1") != 0 && 
					strcasecmp($row['private'], "true") != 0)
				$retArray[] = array("id" => $row['id'],
									"title" => $row['title'],
									"type" => $row['type'],
									"private" => $row['private'],
									"added" => $row['added'],
									"points" => (is_null($row['points']) ? 0 : $row['points']),
									"message" => $row['message']
									);
			}
			mysql_free_result($result);
		}		
		
		return $retArray;
	}
}

?>