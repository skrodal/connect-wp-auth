<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @link       https://github.com/skrodal/feide-connect-wp-auth
 * @author     Simon Skrødal <simon.skrodal@uninett.no>
 *
 * @package    Feide_Connect_Wp_Auth
 * @subpackage Feide_Connect_Wp_Auth/public
 */
class Feide_Connect_Wp_Auth_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	
	#### 
	## Login logo/url/title/button
	####
	// URL
	function fc_login_change_logo_url() { return '#'; } //home_url(); }
	// TITLE
	function fc_login_change_logo_title() { return 'Logg inn med (Feide) Connect'; }
	// LOGO
	function fc_login_change_logo() {
		echo '
				<style type="text/css">
			        .login h1 a {
			            background-image: none, url("' . plugin_dir_url( __FILE__ ) . 'partials/images/uninett_connect.png");
						background-size: 282px 49px;
						height: 49px;
						width: 282px;
			            padding-bottom: 30px;
			        }
			    </style>';
	}	
	
	// Feide Connect BUTTON
	function fc_login_add_feide_connect(){
			include_once('partials/feide-connect-wp-auth-public-login-form.php');
	}
	  

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/feide-connect-wp-auth-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/feide-connect-wp-auth-public.js', array( 'jquery' ), $this->version, false );
	}

}
