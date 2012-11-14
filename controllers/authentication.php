<?php
class authentication extends Public_Controller {
	public function __construct() {
		parent::__construct();
		return;
	}

	// Check Login
	public function checkLogin() {
		$currentMainURL	= $this->uri->slash_segment(1,'both').$this->uri->slash_segment(2);
		$isLoggedIn	= $this->session->userdata('logged_in');
		if(!$isLoggedIn) {
			$this->session->set_userdata("redirectAfterAuth", $currentMainURL);
			$this->checkGoogAuth();
		}
		return;
	}

	// Check Google Auth
	public function checkGoogAuth() {
		$openIDLib	= HOME_APP_DIR.'/lib/GoogleOpenID.php';
		require_once($openIDLib);
		$currentMainURL	= $this->uri->slash_segment(1,'both');
		$responseURL	= $currentMainURL."authentication/returnGoogAuth";
		$googleGateway	= GoogleOpenID::createRequest($responseURL,"ABCDEF",TRUE);
		$googleGateway->redirect();
		return;
	}	

	// Return Google Auth
	public function returnGoogAuth() {
		$openIDLib = HOME_APP_DIR.'/lib/GoogleOpenID.php';
		require_once($openIDLib);
		$googleResponse = GoogleOpenID::create($_GET);
		$sucess         = $googleResponse->success();//true or false
		$user_identity  = $googleResponse->identity();//the user's ID
		$user_email     = $googleResponse->email();//the user's email
		$this->session->set_userdata('email', $user_email);

		$this->accessControl($user_email);

		// Redirect
		$redirect_to = $this->session->userdata('redirectAfterAuth');
		redirect($redirect_to);
		return;
	}	

	// Access Controll
	private function accessControl($user_email) {
		$emailParts	= explode("@",$user_email,2);
		$company	= $emailParts[1];
		$username	= $emailParts[0];
		if($company!='kixeye.com') {
			//echo "<pre> Your email is $user_email </pre>
			//	 <pre> You are NOT KIXEYE employee </pre>";
			//exit;
			$this->checkLogin();
			return;
		} else {
			$authenticanData = array(
				'login_channel'	=> 'OpenId_Gmail',
				'username'	=> $username,
				'logged_in'	=> TRUE,
				);
		}
		$this->session->set_userdata($authenticanData);
		return;
	}	

}// End of class
?>
