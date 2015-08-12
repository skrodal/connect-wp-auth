<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @link       https://github.com/skrodal/feide-connect-wp-auth
 * @author     Simon Skrødal <simon.skrodal@uninett.no>
 *
 * @package    Feide_Connect_Wp_Auth
 * @subpackage Feide_Connect_Wp_Auth/includes
 */
class Feide_Connect_Wp_Auth_Activator {
	
	/**
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// Populate with defaults from config file if no options in DB (e.g. on first install)
		if(!get_option($config['plugin']['name'])) {
			$config = json_decode(file_get_contents( plugin_dir_path( dirname( __FILE__ ))  . 'admin/etc/config_defs.json'), true);
			add_option($config['plugin']['name'], json_encode($config));
		}	
	}
	
}
