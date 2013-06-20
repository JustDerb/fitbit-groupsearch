<?php

require_once('sql_functions.php');

class NoGroupFound extends Exception {

}

class FitbitGroup {
	
	private $groupID;
	
	private $str_name = "Undefined";
	private $str_description = "No description";
	private $int_members = 0;
	private $dt_lastUpdatedInternal;
	private $int_activeness;
	
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
			$this->int_activeness = 0;
			//$this->dt_lastUpdatedInternal = 0;
		}
		else
		{
			throw new NoGroupFound('No group found by ID: '.$this->groupID);
		}
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
}

?>