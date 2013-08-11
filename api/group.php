<?php

require_once('sql_functions.php');

class NoGroupFound extends Exception {

}

class FitbitGroup {
	
	private $groupID;
	
	private $str_name = "Undefined";
	private $str_description = "No description";
	private $int_members = 0;
	
	// 30 day stats (latest)
	private $dbl_latestSteps;
	private $dbl_latestActivePoints;
	private $dbl_latestDistance;
	private $dbl_latestVeryActive;

	// Last updated in the database
	private $dt_lastUpdatedInternal;
	private $dt_lastUpdatedForum;
	
	// TODO - Create algorithm to figure this out
	private $int_activeness = 0;
	
	public function __construct($groupID) {
		$this->groupID = $groupID;
		
		// Grab our information
		$this->init();
	}
	
	private function init() {
		global $st_sql;
		$groupID_encoded = st_mysql_encode($this->groupID, $st_sql);
	
		// Grab our SQL information
		$groupQuery  = "SELECT * ";
		$groupQuery .= "FROM  `groups` ";
		$groupQuery .= "WHERE  `groupid` = '".$groupID_encoded."' ";
				
		$result = mysql_query($groupQuery, $st_sql);
		
		if (mysql_num_rows($result) > 0)
		{
			$data = mysql_fetch_array($result);
			$this->str_name = $data['name'];
			$this->str_description = $data['description'];
			$this->int_members = $data['members'];
			$this->dbl_latestSteps = $data['steps'];
			$this->dbl_latestActivePoints = $data['activepoints'];
			$this->dbl_latestDistance = $data['distance'];
			$this->dbl_latestVeryActive = $data['veryactive'];

			$this->dt_lastUpdatedInternal = $data['updated'];
			//$this->dt_lastUpdatedForum = 0;
		}
		else
		{
			throw new NoGroupFound('No group found by ID: '.$this->groupID);
		}
	}

	public function getLastUpdateDateTime() {
		return $this->dt_lastUpdatedInternal;
	}
	
	public function getGroupID() {
		return $this->groupID;
	}
	
	public function getName() {
		return $this->str_name;
	}
	
	public function getDescription() {
		return $this->str_description;
	}

	public function getNumberOfMembers() {
		return $this->int_members;
	}

	public function getActiveness() {
		return $this->int_activeness;
	}
	
	public function getLatestSteps() {
		return $this->dbl_latestSteps;
	}
	
	public function getLatestActivePoints() {
		return $this->dbl_latestActivePoints;
	}

	public function getLatestDistance() {
		return $this->dbl_latestDistance;
	}

	public function getLatestVeryActive() {
		return $this->dbl_latestVeryActive;
	}
	
	private function getRange($daysBehind, $type) {
		global $st_sql;
		$groupID_encoded = st_mysql_encode($this->groupID, $st_sql);
	
		// Grab our SQL information
		$groupQuery  = "SELECT added, ".$type." ";
		$groupQuery .= "FROM  `groupsmetafitbit` ";
		$groupQuery .= "WHERE  `id` = '".$groupID_encoded."' ";
		
		// TODO - Add $daysBehind
		if ($daysBehind > 0)
			$groupQuery .= " AND added > DATE_ADD(NOW(), INTERVAL -30 DAY)";
				
		$result = mysql_query($groupQuery, $st_sql);
		
		$resultsArr = array();
		
		while ($row = mysql_fetch_assoc($result)) {
			$resultsArr[] = [$row['added'], $row[$type]];
		}
		
		return $resultsArr;
	}
	
	public function getRangeSteps($daysBehind = -1) {
		return $this->getRange($daysBehind, 'stat30steps');
	}
	
	public function getRangeActivePoints($daysBehind = -1) {
		return $this->getRange($daysBehind, 'stat30activepoints');
	}
	
	public function getRangeDistance($daysBehind = -1) {
		return $this->getRange($daysBehind, 'stat30distance');
	}
	
	public function getRangeVeryActive($daysBehind = -1) {
		return $this->getRange($daysBehind, 'stat30veryactive');
	}

}

?>