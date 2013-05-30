<?php
/*
Plugin Name: Wordpress / Mill33
Plugin URI: http://github.com/tomdalynh/wp_mill33
Description: A plugin to enable signup to Mill33's signup API.
Author: Tom Daly
Version: 0.001
Author URI: http://twitter.com/tomdalynh
*/

define('WP_MILL33_DIR', plugin_dir_path(__FILE__));
define('WP_MILL33_URL', plugin_dir_url(__FILE__));

register_activation_hook(__FILE__, 'wp_mill33_activation');
register_deactivation_hook(__FILE__, 'wp_mill33_deactivation');

function wp_mill33_activation() {
    
	//actions to perform once on plugin activation go here   

	//register uninstaller
  register_uninstall_hook(__FILE__, 'wp_mill33_uninstall');

}

function wp_mill33_deactivation() {    
	// actions to perform once on plugin deactivation go here	    
}

function wp_mill33_uninstall() {
	// action to perform upon install go here
}

// add the admin options page
add_action('admin_menu', 'wp_mill33_admin_add_page');

function wp_mill33_admin_add_page() {
	add_options_page(
		'Wordpress Mill33.com Integration Plugin Page',
		'Wordpress Mill33.com Integration',
		'manage-options',
		'wp_mill33',
		'wp_mill33_options_page'
	);
}

// display the admin options page
function wp_mill33_options_page () {
?>

<div>
	<h2>Wordpress Mill33.com Integration Plugin Menu</h2>
	Options relating to the Wordpress Mill33.com Integration Plugin.
	<form action="options.php" method="post">
		<?php settings_fields('wp_mill33_options_group'); ?>
		<?php do_settings_sections('wp_mill33'); ?>
	<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
	</form>
</div>

<?php
}

add_action('admin_init', 'wp_mill33_admin_init');

function wp_mill33_admin_init() {
	register_setting('wp_mill33_options_group', 'wp_mill33_apikey_value', 'plugin_options_validate');
	register_setting('wp_mill33_options_group', 'wp_mill33_listid_value', 'plugin_options_validate');

	add_settings_section('plugin_main', 'Main Settings', 'plugin_section_text', 'wp_mill33');
	add_settings_field('wp_mill33_apikey_id', 'Specify your Mill33.com API key', 'wp_mill33_apikey_field_callback', 'wp_mill33', 'plugin_main');
	add_settings_field('wp_mill33_listid_id', 'Specify your Mill33.com List ID', 'wp_mill33_listid_field_callback', 'wp_mill33', 'plugin_main');
	
}

function plugin_section_text() {
	echo '<p>Specify your Mill33.com information.</p>';
}

function wp_mill33_apikey_field_callback() {
	$options = get_option('wp_mill33_apikey_value');
	echo "<input id='wp_mill33_apikey_id' name='wp_mill33_apikey_value[text_string]' size='50' type='text' value='{$options['text_string']}' />";
}

function wp_mill33_listid_field_callback() {
	$options = get_option('wp_mill33_listid_value');
	echo "<input id='wp_mill33_listid_id' name='wp_mill33_listid_value[text_string]' size='50' type='text' value='{$options['text_string']}' />";
}

// validate our options
function plugin_options_validate($input) {
	$newinput['text_string'] = trim($input['text_string']);
	return $newinput;
}

add_shortcode('wp_mill33_subscribe', 'wp_mill33_subscribe_form');

function wp_mill33_subscribe_form($atts) {
	extract( shortcode_atts( array(
		'listid' => '0',
		'listname' => 'default',
		'contactemail' => 'someone@example.com',
		), $atts ));


	ob_start();
	
	$apikey_value = get_option('wp_mill33_apikey_value');
	$listid_value = $listid;
	$listname_value = $listname;
	$contactemail_value = $contactemail;

	$apikey_value = $apikey_value['text_string'];
	
	include(WP_MILL33_DIR . '/include/form.php');
	
	$output_string=ob_get_contents();
	ob_end_clean();

	return $output_string;
}


