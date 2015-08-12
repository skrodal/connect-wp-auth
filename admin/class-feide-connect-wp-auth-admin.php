<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @link       https://github.com/skrodal/feide-connect-wp-auth
 * @author     Simon Skrødal <simon.skrodal@uninett.no>
 *
 * @package    Feide_Connect_Wp_Auth
 * @subpackage Feide_Connect_Wp_Auth/admin
 */
 
session_start();
// session_destroy();

class Feide_Connect_Wp_Auth_Admin {

	//
	private $plugin_name;
	//
	private $version;
	// 
	private $feide_connect;
	

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name   The name of this plugin.
	 * @param      string    $version    	The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		// 
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		// Our Feide Connect OAuth class.
		$this->feide_connect = new Feide_Connect_Wp_Auth_Login($this->plugin_name);
		
	
		// If token is present AND userinfo not yet collected 
		if($this->feide_connect->getToken(false) !== null && !isset($_SESSION['fc-user-info'])) {
			//
			$userInfo = $this->feide_connect->getUserInfo();
			// 
			if(isset($userInfo['user'])) {
				
				$fcUserName = $userInfo['user']['userid'];		// Core userinfo scope
				$fcUserNiceName = $userInfo['user']['name'];	// Core userinfo userinfo
				$fcUserEmail = isset($userInfo['user']['email']) ? $userInfo['user']['email'] : null; // Requires extra scope; userinfo-mail
				
				// Store in session
				$_SESSION['fc-user-info'] = $userInfo['user'];
				// Check
				$wpUserID = username_exists( $fcUserName );
				// If user exist
				if($wpUserID) {
					$this->fc_login_user($wpUserID);
				} else {
					$nameArr = explode(' ', $fcUserNiceName);
					$wpUserdata = array(
					    'user_login'  	=> 	$fcUserName,
					    'user_pass'   	=> 	wp_generate_password(),
						'description'	=> 	'Registered with (Feide) Connect', 
						'user_email'	=> 	$fcUserEmail,
						'first_name'	=>	$nameArr[0],
						'last_name'		=>	$nameArr[count($nameArr)-1],	
						'display_name'	=> 	$fcUserNiceName,			// Full name - will be shown on the site.
						'user_nicename'	=>	$fcUserNiceName,			// URL-friendly name
						'nickname'		=>	$nameArr[0]					// Pull first name
					);
					// Create user
					$wpUserID = wp_insert_user( $wpUserdata ) ;
					//On success
					if( !is_wp_error($wpUserID) ) {
						echo "User created : ". $wpUserID;
						$this->fc_login_user($wpUserID);
					}
				}
	
			} else {
				// ERROR - user info was not returned
			}
			
			
		} // Done login/registration
	
	}
	
	// 
	private function fc_login_user($wpUserID){
		$wpUser = get_user_by( 'id', $wpUserID ); 
		// Log in user
		if( $wpUser ) {
		    wp_set_current_user( $wpUserID, $wpUser->user_login );
		    wp_set_auth_cookie( $wpUserID );
		    do_action( 'wp_login', $wpUser->user_login, $wpUser );
			echo "User logged in : ". $wpUserID;
		}
	}
	
	// Hooked onto 'wp_logout' event 
	public function fc_logout_handler(){
		$this->feide_connect->reset();
	}
		
	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
	    add_options_page( '(Feide) Connect Configuration and Activation', '(Feide) Connect', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
	    );
	}

	 /**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
	   $settings_link = array(
	    '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
	   );
	   return array_merge(  $settings_link, $links );
	}
	
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_setup_page() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
	    include_once( 'partials/feide-connect-wp-auth-admin-display.php' );
	}
	
	
	/**
	 * Update settings to DB table
	 *
	 */
	public function options_update() {
    	register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
 	}
	
	/**
	 * Validate settings form
	 *
	 */
	public function validate($input) {
	    // Inputs        
	    $options = array();
	
        $options['plugin']['enabled']			= (isset($input['plugin_enabled']) && !empty($input['plugin_enabled'])) ? 1 : 0;
		
        $options['client']['id']				= esc_attr($input['client_id']);
        $options['client']['secret']			= esc_attr($input['client_secret']);
        $options['client']['redirect_url']		= esc_url($input['redirect_url']);
		
        $options['endpoints']['authorization']	= esc_url($input['ep_auth']);
        $options['endpoints']['token']			= esc_url($input['ep_token']);
        $options['endpoints']['userinfo']		= esc_url($input['ep_userinfo']);
		$options['endpoints']['groups']			= esc_url($input['ep_groups']);
	    
	    return json_encode($options);
	 }


	
	






	
	// Register OAuth querystring variables (can then be used by oauth_redirect_handler())
	function oauth_qvar_filter($vars) {
		$vars[] = 'login';
		$vars[] = 'code';
		return $vars;
	}
	
	// Handle events related to login/oauth flow
	function oauth_redirect_handler() {
		// 
		if (get_query_var('login') || get_query_var('code')) {
			// Only if user is not already logged in
			if (!is_user_logged_in()){
				// If login was requested
				$login = get_query_var('login') === "feideconnect";		
				// Initial callback 
				$this->feide_connect->callback();
				// Get token if login request was issued and a token is not already registered
				$token = $this->feide_connect->getToken($login);
				
				
			}  else {
				// User was already logged in - go back to front
				wp_safe_redirect(home_url());
				die();
			}
			
		}
		
		
	}
	
	
	
	
	
	
	
	
	
	
	

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/feide-connect-wp-auth-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/feide-connect-wp-auth-admin.js', array( 'jquery' ), $this->version, false );
	}
}
