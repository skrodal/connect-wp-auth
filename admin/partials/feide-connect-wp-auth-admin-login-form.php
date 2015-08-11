<?php

/**
 * Provide a login button
 *
 *
 * @link       https://github.com/skrodal/
 * @since      1.0.0
 *
 * @package    Feide_Connect_Wp_Auth
 * @subpackage Feide_Connect_Wp_Auth/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

	<?php 
		$loginout = is_user_logged_in() ? '?logout=1' : '?login=1';
		$loginout_text = is_user_logged_in() ? 'Log out' : 'Authenticate with (Feide) Connect';
	?>
	
	<a href="<?php echo esc_url( home_url( '/' ) ) . $loginout ?>" class="button button-primary" style="width: 100%; text-align: center;"><?php echo $loginout_text; ?></a>
	
	
	
	
	<p style="clear: both; margin-top: 80px;">...or use standard Wordpress authentication:</p>
		




<?php

 //.= "<a id='wpoa-login-" . $provider . "' class='wpoa-login-button' href='" . $atts['site_url'] . "?connect=" . $provider . $atts['redirect_to'] . "'>";


?>



