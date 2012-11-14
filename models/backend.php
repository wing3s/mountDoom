<?php

class backend extends CI_Model {
	var $username;
	public function __construct() {
		parent::__construct();
		date_default_timezone_set('America/Los_Angeles');
		$dbConfigLabelBI = 'bi_dev_wen';
		$this->dbBI = $this->load->database($dbConfigLabelBI,TRUE,TRUE);
		if(!$this->dbBI) {
			echo "No database connection!\n";
			exit;
		}	
		return;
	}

	// Check if user is registered (exists in md_user)
	public function checkUserExist() {
		$username	= $this->username;
		if($this->dbBI->get_where('md_user',array('username'=>$username))->num_rows()==0) {
			return FALSE;
		} else	{
			return TRUE;
		}
	}

	// Get all users
	// return username => userfullname as array
	public function getUsers() {
		$userArr= array();
		$sql	= "
			SELECT username, CONCAT(first_name,' ',last_name) fullname
			FROM md_user
			ORDER BY first_name, last_name
			";
		$result	= $this->dbBI->query($sql)->result_array();
		foreach($result as $row) {
			$userArr[$row['username']]	= $row['fullname'];
		}
		return $userArr;
	}

	// Get all group names
	// return groupname => groupfullname as array
	public function getGroups($type=NULL) {
		$groupArr	= array();
		if(!$type) {
			$result	= $this->dbBI->order_by("sequence asc,groupname asc")->select('groupname,group_fullname')->get('md_group')->result_array();
		} else	{
			$result	= $this->dbBI->order_by("groupname","asc")->select('groupname,group_fullname,type')->get_where('md_group',array('type'=>$type))->result_array();
		}
		foreach($result as $row) {
			$groupArr[$row['groupname']]	= $row['group_fullname'];
		}
		return $groupArr;
	}

	// Check if the group is in groups
	public function inGroup($name) {
		if($this->dbBI->get_where('md_group',array('groupname'=>$name))->num_rows()==0) {
			return FALSE;
		}	else	{
			return TRUE;
		}
	}

	// Get user's group name
	public function getUserGroup() {
		$username   = $this->username;
		return $this->dbBI->get_where('md_user',array('username'=>$username))->row()->groupname;
	}

	// Get all members in group
 	public function getGroupMembers($name) {
		if(!$name) {
			echo "Need group name for getGroupMemebers\n";
			exit;
		}
		$result	= $this->dbBI->select('username')->get_where('md_user',array('groupname'=>$name))->result_array();
		$memberArr	= array();
		foreach($result as $row) {
			$memberArr[]	= $row['username'];
		}
		return $memberArr;
	}

	// Get Info of group or user
	public function getInfo($type,$name) {
		if(!$type or !$name) {
			echo "Need type & name\n";
			exit;
		}
		$table		= "md_".$type;
		$condition	= $type."name";
		return $this->dbBI->get_where($table,array($condition=>$name))->row_array();
	}

	// Get Projects of group or user
	public function getProjects($type,$name) {
		if(!$type or !$name) {
			echo "Need type & name\n";
			exit;
		}
		$table		= "md_".$type."_project";
		$condition	= $type."name";
		return $this->dbBI->get_where($table,array($condition=>$name))->result_array();
	}

	// Get Posts from group or user
	// If lastPostID is given, get new posts only
	public function getPosts($type,$name,$lastPostID=NULL,$year=NULL,$week=NULL) {
		if(!$type or !$name) {
			echo "Need type & name in getPosts\n";
			exit;
		}
		$getNewPosts= "";
		if($lastPostID) {
			$getNewPosts=" AND post_id > $lastPostID ";
		}

		$condition	= $type."name";
		if(!$week) {
			$week=date("W");
		}
		if(!$year) {
			$year=date("Y");
		}
		$nextWeek	= $week+1;
		$weekStart	= strtotime("$year-W$week");
		$weekEnd	= strtotime("$year-W$nextWeek");
		$sqlPost	=
			"
			SELECT p.*, CONCAT(u.first_name,' ',u.last_name) user_fullname,
				CONCAT(boss.first_name,' ', boss.last_name) boss_fullname ,
				CONCAT(bigboss.first_name,' ',bigboss.last_name) bigboss_fullname
			FROM md_post p
			JOIN md_user u
			ON u.username	 = p.username
			LEFT JOIN md_user boss
			ON p.boss	= boss.username
			LEFT JOIN md_user bigboss
			ON p.bigboss	= bigboss.username 
			WHERE (u.$condition = '$name' OR p.location = '$name')
			AND p.add_utc > $weekStart
			AND p.add_utc	< $weekEnd
			$getNewPosts
			ORDER BY p.add_utc DESC
			";
		return $this->dbBI->query($sqlPost)->result_array();
	}

	public function getPostByID($id) {
		if(!$id) { 
			echo "Need id for getPostByID\n"; 
			exit;
		}
		$sqlPost	= 
			"
			SELECT p.*, CONCAT(u.first_name,' ',u.last_name) user_fullname,
				CONCAT(boss.first_name,' ', boss.last_name) boss_fullname ,
				CONCAT(bigboss.first_name,' ',bigboss.last_name) bigboss_fullname
			FROM md_post p
			JOIN md_user u
			ON u.username	 = p.username
			LEFT JOIN md_user boss
			ON p.boss		= boss.username
			LEFT JOIN md_user bigboss
			ON p.bigboss	= bigboss.username 
			WHERE p.post_id = $id
			";
		return $this->dbBI->query($sqlPost)->row_array();
	}

	public function searchPosts($field,$keyword) {
		if(!$field) { 
			echo "Need field for searchPosts\n";	
			exit;
		}
		if(!$keyword) { 
			echo "Need keyword for searchPosts\n";
			exit;
		}
		switch($field) {
			case 'post_content':
				$condition	= "p.post_content LIKE '%$keyword%'";
				break;
			case 'author':
				$condition	= "u.first_name LIKE '%$keyword%'
						OR u.last_name  LIKE '%$keyword%'";
				break;
			default:
				echo "Wrong field in searchPosts!\n";
				return;	
		}
		$sqlPost	= 
			"
			SELECT p.*, CONCAT(u.first_name,' ',u.last_name) user_fullname,
				CONCAT(boss.first_name,' ', boss.last_name) boss_fullname ,
				CONCAT(bigboss.first_name,' ',bigboss.last_name) bigboss_fullname
			FROM md_post p
			JOIN md_user u
			ON u.username	 = p.username
			LEFT JOIN md_user boss
			ON p.boss	= boss.username
			LEFT JOIN md_user bigboss
			ON p.bigboss	= bigboss.username 
			WHERE $condition
			";
		return $this->dbBI->query($sqlPost)->result_array();
	}

	// Get Comments for that post
	// If lastCommentID is given, get new comments only
	public function getComments($postID,$lastCommentID=NULL) {
		if(!$postID) {
			echo "Need postID for getComments\n";
			exit;
		}
		$getNewComments	= "";
		if($lastCommentID) {
			$getNewComments=" AND c.comment_id > $lastCommentID ";
		}
		$sqlComment	=
			"
			SELECT c.*, u.last_name, u.first_name
			FROM md_comment c
			JOIN md_user u
			ON c.username = u.username
			WHERE c.post_id = $postID
			$getNewComments
			ORDER BY c.comment_id ASC
			";
		return $this->dbBI->query($sqlComment)->result_array();	
	}

	public function getFullName($name,$type='long') {
		if(!$name) {
			echo "Need location for getFullName\n";
			exit;
		}
		$nameQuery	= $this->dbBI->select('group_fullname')->get_where('md_group',array('groupname'=>$name))->row_array();
		if(count($nameQuery)>0) {
			$fullName	= $nameQuery['group_fullname'];
		}	else {
			switch($type) {
				case 'long':
					$lastName	= "last_name";
					break;
				case 'short':
					$lastName	= "SUBSTR('last_name',1,1)";
					break;
			}
			$sql	= 
				"
				SELECT CONCAT(first_name,' ', $lastName ) as fullName
				FROM md_user
				WHERE username = '$name'
				";
			$fullName	= $this->dbBI->query($sql)->row()->fullName;
		}
		return $fullName;
	}


} // End of Class
