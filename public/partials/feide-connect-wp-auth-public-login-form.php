<?php

/**
 * Provides extra elements/functionalities to the login form.
 *
 * @since      1.0.0
 * @link       https://github.com/skrodal/feide-connect-wp-auth
 * @author     Simon Skrødal <simon.skrodal@uninett.no>
 *
 * @package    Feide_Connect_Wp_Auth
 * @subpackage Feide_Connect_Wp_Auth/public/partials
 */
?>


	<?php
		// ?login=1 is the trigger that sets off the Feide Connect auth flow
		$loginout = is_user_logged_in() ? wp_logout_url() : esc_url( home_url( '/?login=feideconnect' ));
		// 
		$loginout_text = is_user_logged_in() ? 'Log out' : 'Authenticate with (Feide) Connect';
	?>
	
	<a href="<?php echo $loginout; ?>" class="button button-primary" style="width: 100%; text-align: center;"><?php echo $loginout_text; ?></a>
		
	<p style="clear: both; margin-top: 80px;">...or use standard Wordpress authentication:</p>