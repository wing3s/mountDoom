<?php

require 'authentication.php';

class mountDoom  extends authentication {
	public function __construct() {
		parent::__construct();
		date_default_timezone_set('America/Los_Angeles');
		// Google Auth
		$this->checkLogin();
		$this->email	= $this->session->userdata('email');
		$this->username	= $this->session->userdata('username');

		// Load models
		$this->load->model('backend','backend');
		$this->backend->username	= $this->username;
		$this->load->model('frontend','frontend');
		$this->frontend->username	= $this->username;

		// Load form for VIEW
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->mainURL	= $this->uri->slash_segment(1,'both').$this->uri->slash_segment(2);
		return;
	}

	// TEST
	public function test() {
		$memberArr	= $this->backend->getGroupMembers('AE');
		print_r($memberArr);
		return;
	}
	// Main Page
	public function index($name=NULL) {
		$userExist = $this->backend->checkUserExist();
		if($userExist == FALSE) {
			$this->registerPage();
			return;
		}
		if(!$name) {
			$group	= $this->backend->getUserGroup();
			$this->showPage('group',$group);
		}	elseif($this->backend->inGroup($name))	{
			$this->showPage('group',$name);
		}	else	{
			$this->showPage('user',$name);
		}
		return;
	}

	// Page of group or user
	private function showPage($type,$name) {
		if(!$type or !$name) {
			echo "Need type & name in showPage\n";
			exit;
		}
		// (1) Load Info
		$info		= $this->backend->getInfo($type,$name);
		if($info == NULL) {
			$this->index();
			return;
		}
		$profileView	= $this->getProfileView($type,$name);
		if($type == 'group') {
			$fullName	= $info['group_fullname'];
		} else if ($type=='user') {
			$fullName	= $info['first_name']." ".$info['last_name'];
		}
		// (2) Load Projects
		$projects	= $this->backend->getProjects($type,$name);
		// (3) Load Posts
		$posts		= $this->backend->getPosts($type,$name);
		$postsView	= $this->getPostsView($posts);

		// (4) Load All Groups
		$allGroups	= $this->backend->getGroups();
		// (5) Gather data
		$timelineData	= array(
			'mainURL'	=> $this->mainURL,
			'location'	=> $name,
			'userList'	=> $this->backend->getUsers(),
			'posts'		=> $postsView
			);
		$headerData	= array(
			'name'		=> $name,
			'mainURL'	=> $this->mainURL,
			'fullName'	=> $fullName,
			'profileView'	=> $profileView,
			);
		$navigationData	= array(
			'mainURL'	=> $this->mainURL,
			'allGroups'	=> $allGroups,
			);
		$this->load->view('head');
		$this->load->view('header',$headerData);
		$this->load->view('timeline_style');
		$this->load->view('timeline',$timelineData);
		$this->load->view('navigation',$navigationData);
		$this->load->view('ads');
		$this->load->view('foot');
		if(!$this->username) {
			return;
		}
		$this->frontend->saveUserActivity($this->username,$name);
		return;;
	}

	// Input Posts array and return PostsView array
	public function getPostsView($posts) {
		if(!$posts) { 
			return; 
		}
		if(!is_array($posts)) {
			echo "Wrong data type for posts as array in getPostsView\n"; exit;
		}
		$postsView	= array();
		foreach($posts as $post) {
			$postID		= $post['post_id'];
			$postHit	= $this->getHitDetail('post',$postID);
			// (3.5) Load Comments
			$comments	= $this->backend->getComments($postID);
			$commentsView	= array();
			foreach($comments as $comment) {
				$commentID	= $comment['comment_id'];
				$commentHit	= $this->getHitDetail('comment',$commentID);
				$commentData	= array(
					'mainURL'	=> $this->mainURL,
					'comment'	=> $comment,
					'commentHit'	=> $commentHit,
					);
				$commentsView[]	= $this->load->view('comment',$commentData,TRUE);
			}
			$post['locationFullName']	= $this->backend->getFullName($post['location']);
			$postData	= array(
				'mainURL'	=> $this->mainURL,
				'post'		=> $post,
				'postHit'	=> $postHit,
				'comments'	=> $commentsView,
				);
			$postsView[]= $this->load->view('post',$postData,TRUE);
		}
		return $postsView;
	}

	public function getProfileView($type,$name) {
		if(!$type or !$name) {
			echo "Need type,view for getProfileView\n";
			exit;
		}
		$data['mainURL']	= $this->mainURL ;
		if($type == 'group') {
			$data['groupMembers']	= $this->backend->getGroupMembers($name);
		}	else	{
			$data['username']	= $name;	
		}
		return $this->load->view($type.'profile',$data,TRUE);
	}

	// Search Posts by keyword and echo it
	public function searchAllPosts() {
		$this->form_validation->set_rules('keyword','keyword','required');
		if($this->form_validation->run() == FALSE) {
			return;
		}
		$inputData	= $this->input->post();
		$keyword	= $inputData['keyword'];
		$searchResults	= array();
		$searchResults	+= $this->backend->searchPosts('post_content',$keyword);
		$searchResults	+= $this->backend->searchPosts('author',$keyword);
		$postsView	= $this->getPostsView($searchResults);

		foreach($postsView as $postView) {
			echo $postView;
		}

		$this->frontend->saveSearchHistory($this->username,$keyword);
		return;
	}

	// Add new Post and echo it
	public function addPost($location) {
		$this->form_validation->set_rules('post_content','post_content','required');
		if($this->form_validation->run() == FALSE) {
			return;
		}
		$addPostData	= $this->frontend->getInput();
		$addPostData['location']	= $location;
		$this->frontend->dbBI->insert('md_post',$addPostData);
		$postID		= $this->frontend->dbBI->insert_id();
		$postHit	= $this->getHitDetail('post',$postID);
		$returnPost	= $this->backend->getPostByID($postID);
		$returnPost['locationFullName']	= $this->backend->getFullName($returnPost['location']);
		$returnPostData	= array(
			'mainURL'	=> $this->mainURL,
			'post'		=> $returnPost,
			'postHit'	=> $postHit,
			'comments'	=> array()
			);
		$this->load->view('post',$returnPostData);
		return;
	}
	// Delete job status
	public function deletePost($postID) {
		return;
	}


	// Send hit
	// $type = 'post' or 'comment'
	public function sendHit($type,$sourceID) {
		$this->frontend->updateHit($type,$sourceID);
		echo $this->getHitDetail($type,$sourceID);
		return;
	}


	public function getHitDetail($type,$sourceID) {
		$hitData	= $this->frontend->getHits($type,$sourceID);
		if(!$hitData) { 
			echo "";
			return;
		}
		$hitData['mainURL']	= $this->mainURL;
		$hitData['type']	= $type;
		return $this->load->view('hit',$hitData,TRUE);
	}
	// Send end of status
	public function endPost($postID){
		return;
	}

	// Give comment
	public function addComment($postID) { 
		$this->form_validation->set_rules('comment','comment','required');
		if($this->form_validation->run() == FALSE) {
			return;
		}
		$addCommentData	= $this->frontend->getInput();
		$addCommentData['post_id']	= $postID;
		$this->frontend->dbBI->insert('md_comment',$addCommentData);
		$commentID	= $this->frontend->dbBI->insert_id();
		$commentHit     = $this->getHitDetail('comment',$commentID);
		$infoData	= $this->backend->getInfo('user',$this->username);
		$returnComment	= $this->frontend->dbBI->get_where('md_comment',array('comment_id'=>$commentID))->row_array();
		$returnComment['last_name']	= $infoData['last_name'];
		$returnComment['first_name'	= $infoData['first_name'];
		$returnCommentData	= array(
			'mainURL'	=> $this->mainURL,
			'comment'	=> $returnComment,
			'commentHit'=> $commentHit,
			);
		$this->load->view('comment',$returnCommentData); 
		return;
	}

	// Delete comment
	public function deleteComment($commentID) {
		return;
	}

	// Not used
	public function getUserList() {
		$userArr	= $this->backend->getUsers();
		$outputArr	= array();
		foreach($userArr as $username=>$userFullName) {
			$outputArr[]	= array(
				'label'	=> $userFullName,
				'value'	=> $username,
				);
		}
		return $outputArr;
	}

	// Register new user
	public function registerPage() {
		$groups	= $this->backend->getGroups('official');
		$data	= array(
			'groups'	=> $groups,
			'mainURL'	=> $this->mainURL,
			);
		$this->load->view('head');
		$this->load->view('register',$data);
		$this->load->view('foot');
		return;
	}

	// Register New user, if existed before, ignore
	public function registerNewUser() {
		$this->form_validation->set_rules('first_name','first_name','required');
		if($this->form_validation->run() == FALSE) {
			$this->index();
			return;
		}
		$registerData	= $this->frontend->getInput();
		$insertQuery	= $this->backend->dbBI->insert_string('md_user',$registerData);
		$insertQuery	= str_replace('INSERT INTO','INSERT IGNORE INTO',$insertQuery);
		$this->backend->dbBI->query($insertQuery);
		redirect($this->mainURL);
		return;
	}

} // End of Class
?>
