<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/skrodal/
 * @since             1.0.0
 * @package           Feide_Connect_Wp_Auth
 *
 * @wordpress-plugin
 * Plugin Name:       (Feide)Connect Wordpress Auth
 * Plugin URI:        https://github.com/skrodal/feide-connect-wp-auth
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Simon Skrødal
 * Author URI:        https://github.com/skrodal/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       feide-connect-wp-auth
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-feide-connect-wp-auth-activator.php
 */
function activate_feide_connect_wp_auth() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-feide-connect-wp-auth-activator.php';
	Feide_Connect_Wp_Auth_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-feide-connect-wp-auth-deactivator.php
 */
function deactivate_feide_connect_wp_auth() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-feide-connect-wp-auth-deactivator.php';
	Feide_Connect_Wp_Auth_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_feide_connect_wp_auth' );
register_deactivation_hook( __FILE__, 'deactivate_feide_connect_wp_auth' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-feide-connect-wp-auth.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_feide_connect_wp_auth() {

	$plugin = new Feide_Connect_Wp_Auth();
	$plugin->run();

}
run_feide_connect_wp_auth();
