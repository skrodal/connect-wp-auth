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
        $options = get_option($this->plugin_name);

        // 
        $enabled = $options['enable_plugin'];
        $client_id = $options['client_id'];
		
        $client_secret = $options['client_secret'];
        $redirect_url = $options['redirect_url'];
        $auth_endpoint = $options['auth_endpoint'];
        $token_endpoint = $options['token_endpoint'];
        $userinfo_endpoint = $options['userinfo_endpoint'];
    	// 
        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);
    ?>
		
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Enable Plugin:</th>
					<td>
						<input type="checkbox" id="<?php echo $this->plugin_name; ?>-enable_plugin" name="<?php echo $this->plugin_name; ?>[enable_plugin]" value="1" <?php checked($enabled, 1); ?>/>
					</td>
				</tr>
			
				<tr valign="top">
					<th scope="row">Client ID:</th>
					<td>
						<input type="text" id="<?php echo $this->plugin_name; ?>-client_id" name="<?php echo $this->plugin_name; ?>[client_id]"  value="<?php if(!empty($client_id)) echo $client_id; //d1ce8192-fc2a-4046-938a-655ffc5be571 ?>"/> 
					</td>
				</tr>
			
			<tr valign="top">
				<th scope="row">Client Secret:</th>
				<td>
					<input type="text" id="<?php echo $this->plugin_name; ?>-client_secret" name="<?php echo $this->plugin_name; ?>[client_secret]" value="<?php if(!empty($client_secret)) echo $client_secret; //20b53caa-0dd2-4ea7-9a92-9e3ebbeb239e?>"/>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Redirect URL:</th>
				<td>
					<input type="url" id="<?php echo $this->plugin_name; ?>-redirect_url" name="<?php echo $this->plugin_name; ?>[redirect_url]" value="<?php if(!empty($redirect_url)) echo $redirect_url; //https://127.0.0.1/sites/wp-dev/?>"/>
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
						<input type="url" id="<?php echo $this->plugin_name; ?>-auth_endpoint" name="<?php echo $this->plugin_name; ?>[auth_endpoint]" value="<?php if(!empty($auth_endpoint)) echo $auth_endpoint; //https://auth.feideconnect.no/oauth/authorization ?>"/>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Token Endpoint:</th>
					<td>
						<input type="url" id="<?php echo $this->plugin_name; ?>-token_endpoint" name="<?php echo $this->plugin_name; ?>[token_endpoint]" value="<?php if(!empty($token_endpoint)) echo $token_endpoint; //https://auth.feideconnect.no/oauth/token ?>"/>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Userinfo Endpoint:</th>
					<td>
						<input type="url" id="<?php echo $this->plugin_name; ?>-userinfo_endpoint" name="<?php echo $this->plugin_name; ?>[userinfo_endpoint]" value="<?php if(!empty($userinfo_endpoint)) echo $userinfo_endpoint; //https://auth.feideconnect.no/userinfo ?>"/>
					</td>
				</tr>
			
			</tbody>
		</table> <!-- .form-table -->
		
		<?php submit_button('Save', 'primary','submit', TRUE); ?>

	</form>
</div>