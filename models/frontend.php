<?php

class frontend extends backend {
	var $username;
	public function __construct() {
		parent::__construct();
		return;
	}

	// Auto add username & remove 'submit'
	public function getInput() {
		$data   = $this->input->post();
		$data['username']   = $this->username;
		$data['add_utc']    = time();
		unset($data['submit']);
		return $data;
	}

	// UpSert number of hits
	// $type = 'post' or 'comment'
	public function updateHit($type,$sourceID) {
		$table		= "md_".$type."_hit";
		$column		= $type."_id";
		$username	= $this->username;
		$current	= time();
		$sqlHit		= 
			"
			INSERT INTO $table
			(username, $column, count, add_utc)
			VALUES
			('$username', $sourceID, 1, $current)
			ON DUPLICATE KEY UPDATE
			count	= count + 1, 
			add_utc	= $current
			";
		$this->dbBI->query($sqlHit);
		$hitData	= $this->getHits($type,$sourceID);
		return $hitData;
	}

	// Query number of hits
	public function getHits($type,$sourceID) {
		$table	= "md_".$type."_hit";
		$column	= $type."_id";
		$sqlHit	= 
			"
			SELECT sum(h.count) userCount,
				h.username username, 
				CONCAT(u.first_name,' ',SUBSTR(u.last_name,1,1),'.') userFullName
			FROM $table h
			JOIN md_user u
			ON h.username = u.username
			WHERE h.$column = $sourceID
			GROUP BY h.username
			ORDER BY sum(h.count) DESC
			";
		$sqlTotalHit	= 
			"
			SELECT sum(count) totalCount, count(username) totalUser
			FROM $table
			WHERE $column = $sourceID
			";
		$hitData['stats']	= $this->dbBI->query($sqlTotalHit)->row_array();
		$hitData['users']	= $this->dbBI->query($sqlHit)->result_array();
		if($hitData['stats']['totalUser']==0) {
			return NULL;
		}
		return $hitData;
	}

	// 
	public function saveUserActivity($username,$location) {
		$activityData	= array(
			'username'	=> $username,
			'location'	=> $location,
			'add_utc'	=> time(),
			);
		$this->dbBI->insert('md_user_activity',$activityData);
		return;
	}

	public function saveSearchHistory($username,$keyword) {
		$searchData	= array(
			'username'	=> $username,
			'keyword'	=> $keyword,
			'add_utc'	=> time(),
			);
		$this->dbBI->insert('md_user_search',$searchData);
		return;
	}
} // End of Class
