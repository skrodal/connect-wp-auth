<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 * @link       https://github.com/skrodal/feide-connect-wp-auth
 * @author     Simon Skrødal <simon.skrodal@uninett.no>
 *
 * @package    Feide_Connect_Wp_Auth
 * @subpackage Feide_Connect_Wp_Auth/admin/partials
 */
?>


<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

	<h3>Instructions:</h3>
	<ol>
		<li>Create a new client at <a href='https://dashboard.feideconnect.no/' target="_blank">dashboard.feideconnect.no</a>.</li>
		<li>Configure the below fields as per the "OAuth Details" page in the Feide Connect Client Dashboard.</li>
	</ol>
	
	<hr/>
		
	<h3>Client Settings:</h3>
	
	<p>All fields are required.</p>
	
	<form method="post" name="<?php echo $this->plugin_name; ?>" action="options.php">
		
    <?php
        //Grab all options
        $options = json_decode(get_option($this->plugin_name), true);

        // 
        $enabled 		= $options['plugin']['enabled'];
        $client_id 		= $options['client']['id'];
		
        $client_secret	= $options['client']['secret'];
        $redirect_url 	= $options['client']['redirect_url'];
		
        $ep_auth 		= $options['endpoints']['authorization'];
        $ep_token 		= $options['endpoints']['token'];
        $ep_userinfo 	= $options['endpoints']['userinfo'];
		$ep_groups 		= $options['endpoints']['groups'];
    	// 
        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);
    ?>
		
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Enable Plugin:</th>
					<td>
						<input type="checkbox" id="<?php echo $this->plugin_name; ?>-plugin_enabled" name="<?php echo $this->plugin_name; ?>[plugin_enabled]" value="1" <?php checked($enabled, 1); ?>/>
					</td>
				</tr>
			
				<tr valign="top">
					<th scope="row">Client ID:</th>
					<td>
						<input type="text" id="<?php echo $this->plugin_name; ?>-client_id" name="<?php echo $this->plugin_name; ?>[client_id]"  value="<?php if(!empty($client_id)) echo $client_id; ?>"/> 
					</td>
				</tr>
			
			<tr valign="top">
				<th scope="row">Client Secret:</th>
				<td>
					<input type="text" id="<?php echo $this->plugin_name; ?>-client_secret" name="<?php echo $this->plugin_name; ?>[client_secret]" value="<?php if(!empty($client_secret)) echo $client_secret; ?>"/>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Redirect URL:</th>
				<td>
					<input type="url" id="<?php echo $this->plugin_name; ?>-redirect_url" name="<?php echo $this->plugin_name; ?>[redirect_url]" value="<?php if(!empty($redirect_url)) echo $redirect_url; ?>"/>
				</td>
			</tr>
			
			</tbody>
		</table> <!-- .form-table -->
		
		<hr>
		
		<h3>OAuth Provider</h3>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Authorization Endpoint:</th>
					<td>
						<input type="url" id="<?php echo $this->plugin_name; ?>-ep_auth" name="<?php echo $this->plugin_name; ?>[ep_auth]" value="<?php if(!empty($ep_auth)) echo $ep_auth; ?>"/>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Token Endpoint:</th>
					<td>
						<input type="url" id="<?php echo $this->plugin_name; ?>-ep_token" name="<?php echo $this->plugin_name; ?>[ep_token]" value="<?php if(!empty($ep_token)) echo $ep_token; ?>"/>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Userinfo Endpoint:</th>
					<td>
						<input type="url" id="<?php echo $this->plugin_name; ?>-ep_userinfo" name="<?php echo $this->plugin_name; ?>[ep_userinfo]" value="<?php if(!empty($ep_userinfo)) echo $ep_userinfo; ?>"/>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Groups API Endpoint:</th>
					<td>
						<input type="url" id="<?php echo $this->plugin_name; ?>-ep_groups" name="<?php echo $this->plugin_name; ?>[ep_groups]" value="<?php if(!empty($ep_groups)) echo $ep_groups; ?>"/>
					</td>
				</tr>
			
			</tbody>
		</table> <!-- .form-table -->
		
		<?php submit_button('Save', 'primary','submit', TRUE); ?>

	</form>
</div>