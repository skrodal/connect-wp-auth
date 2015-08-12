<?php
/**
 * Feide Connect authentication flow
 *
 * This class handles the auth flow. It is loaded only if relevant query string vars are detected.
 *
 * @since      1.0.0
 * @link       https://github.com/skrodal/feide-connect-wp-auth
 * @author     Simon Skrødal <simon.skrodal@uninett.no>
 *
 * @package    Feide_Connect_Wp_Auth
 * @subpackage Feide_Connect_Wp_Auth/includes
 */
 

class Feide_Connect_Wp_Auth_Login {
	
	protected $CLIENT_ENABLED;
	protected $CLIENT_ID;
	protected $CLIENT_SECRET;
	protected $REDIRECT_URI;
	
	protected $EP_AUTH;
	protected $EP_TOKEN;
	protected $EP_USERINFO;
	protected $EP_GROUPS;
	
	protected $OAUTH_STATE = null;
	protected $OAUTH_TOKEN = null;
	
	protected $plugin_name;
	
	/**
	 * Pull info from options
	 */
	public function __construct($plugin_name) {
		$this->plugin_name = $plugin_name;
        //Grab all options from DB
        $options = json_decode(get_option($plugin_name), true);
		// TODO: Sanitise -> make sure all required options are available.
        // 
        $this->CLIENT_ENABLED 		= $options['plugin']['enabled'];
        $this->CLIENT_ID 			= $options['client']['id'];
        $this->CLIENT_SECRET 		= $options['client']['secret'];
        $this->REDIRECT_URI 		= $options['client']['redirect_url'];
		// 
        $this->EP_AUTH 			= $options['endpoints']['authorization'];
        $this->EP_TOKEN 		= $options['endpoints']['token'];
        $this->EP_USERINFO 		= $options['endpoints']['userinfo'];
		$this->EP_GROUP 		= $options['endpoints']['groups'];
		//
		if (!empty($_SESSION[$this->plugin_name.'_STATE'])) $this->OAUTH_STATE = $_SESSION[$this->plugin_name.'_STATE'];
		if (!empty($_SESSION[$this->plugin_name.'_TOKEN'])) $this->OAUTH_TOKEN = $_SESSION[$this->plugin_name.'_TOKEN'];
	}

	### Feide Connect Functions ###
	
	/**
	 *
	 */
	public function callback() {
		if (empty($_REQUEST['code'])) return null;
		if (empty($_REQUEST['state'])) throw new Exception('Missing state parameter in the response from the OAuth Provider');
		$this->verifyState($_REQUEST['state']);
		$response = $this->resolveCode($_REQUEST['code']);
		$this->setToken($response);
		$this->redirect($this->REDIRECT_URI);
	}
		// Compare state with stored state
	protected function verifyState($state) {
		if ($this->OAUTH_STATE !== $state) throw new Exception('Invalid OAuth state.');
	}	

	/**
	 *
	 */
	public function redirect($url, $q = null) {
		$fullURL = $url;
		if ($q !== null) {
			$qs = http_build_query($q);
			$fullURL .= '?' . $qs;
		}
		header('Location: ' . $fullURL);
		exit;
	}
	
	/**
	 *
	 */
	protected function post($url, $q) {
		// If plugin is not enabled, stop here.
		if (!$this->CLIENT_ENABLED || !$this->CLIENT_ID || !$this->CLIENT_SECRET) {
			die('<div id="message" class="updated">
					<p><strong>Feide Connect Plugin disabled or misconfigured!</strong></p>
    			</div>'
				);
		}
		
		$qs = http_build_query($q);
		$opts = array(
			'http' => array(
				'method'  => 'POST',
				'header'  =>
					"Content-type: application/x-www-form-urlencoded\r\n" .
					"Authorization: Basic " . base64_encode($this->CLIENT_ID . ':' . $this->CLIENT_SECRET),
				'content' => $qs
			)
		);
		$context  = stream_context_create($opts);
		$result = file_get_contents($url, false, $context);
		$data = json_decode($result, true);
		if ($data === null) {
			echo 'Could not parse JSON output from Token endpoint. ' .
				'Debug response from OAUth provider: '; print_r($result); exit;
		}
		return $data;
	}
	
	/**
	 *
	 */
	protected function uuid() {
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff),
			// 16 bits for "time_mid"
			mt_rand(0, 0xffff),
			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand(0, 0x0fff) | 0x4000,
			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand(0, 0x3fff) | 0x8000,
			// 48 bits for "node"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}

	/**
	 *
	 */
	public function authorize() {
		$state = $this->uuid();
		$this->setState($state);
		$q = array(
			'response_type' => 'code',
			'client_id' => $this->CLIENT_ID,
			'redirect_uri' => $this->REDIRECT_URI,
			'state' => $state,
		);
		$this->redirect($this->EP_AUTH, $q);
	}
	
	/**
	 *
	 */
	protected function setState($state) {
		$this->OAUTH_STATE = $state;
		$_SESSION[$this->plugin_name.'_STATE'] = $state;
	}

	/**
	 *
	 */
	public function getToken($get = false) {
		$token = $this->OAUTH_TOKEN;
		if ($token === null && $get) {
			return $this->authorize();
		}
		return $token;
	}
	
	/**
	 *
	 */
	protected function setToken($token) {
		$this->OAUTH_TOKEN = $token;
		$_SESSION[$this->plugin_name.'_TOKEN'] = $token;
	}

	/**
	 *
	 */
	public function reset() {
		$this->setToken(null);
		$this->setState(null);
		session_destroy();
	}	
	
	/**
	 *
	 */
	public function resolveCode($code) {
		$q = array(
			'client_id' => $this->CLIENT_ID,
			'redirect_uri' => $this->REDIRECT_URI,
			'grant_type' => 'authorization_code',
			'code' => $code,
		);
		
		$response = $this->post($this->EP_TOKEN, $q);
		//
		if (empty($response['access_token'])) {
			echo "response was was <pre>"; print_r($response);
			throw new Exception('Response from token endpoint did not contain an access token');
		}
		return $response;
	}
	

	/**
	 *
	 */
	public function isAuthenticated() {
		return $this->OAUTH_TOKEN !== null;
	}
	
	/**
	 *
	 */
	public function getUserInfo() {
		return $this->protectedRequest($this->EP_USERINFO);
	}


	/**
	 *
	 */
	public function get($url) {
		return $this->protectedRequest($url);
	}
	
	/**
	 *
	 */
	protected function protectedRequest($url) {
		if ($this->OAUTH_TOKEN === null) throw new Exception('Cannot get data without a token');
		$opts = array(
			'http' => array(
				'method'  => 'GET',
				'header'  => "Authorization: Bearer " . $this->OAUTH_TOKEN['access_token'],
			),
		);
		$context  = stream_context_create($opts);
		$result = file_get_contents($url, false, $context);
		$data = json_decode($result, true);
		if ($data === null) {
			echo 'Could not parse JSON output from API [' . $url . ']. ';
			echo 'Debug response from API: '; print_r($result);
			exit;
		}
		return $data;
	}
	
}