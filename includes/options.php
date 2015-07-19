<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/10/2015
 * Time: 3:57 PM
 */

add_action('admin_init', 'cds_integration_admin_init');
add_action('admin_menu', 'cds_integration_admin_add_page');

function cds_integration_admin_add_page() {
	add_options_page('CDS Integration Settings', 'CDS Integration', 'manage_options', 'cds-integration',
		'cds_integration_options_page');
}

function cds_integration_options_page() {
	?>
	<div>
		<h2>CDS Integration settings</h2>

		<p>Use this page to configure the CDS Integration plugin.</p>

		<form action="options.php" method="post">
			<?php settings_fields('cds_integration_settings'); ?>

			<?php do_settings_sections('cds-integration'); ?>

			<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>">
		</form>
	</div>
	<?php
}

function cds_integration_admin_init() {
	register_setting('cds_integration_settings', 'cds_integration_settings', 'cds_integration_settings_validate');

	add_settings_section('cds_integration_main', 'Main Settings', 'cds_integration_main_settings_text', 'cds-integration');

	add_settings_field('cds_host', 'CDS Host', 'cds_integration_host_setting', 'cds-integration', 'cds_integration_main');
	add_settings_field('cds_domain', 'CDS Domain', 'cds_integration_domain_setting', 'cds-integration', 'cds_integration_main');
}

function cds_integration_settings_validate($input) {
	$newinput = array();

	$newinput['cds_host'] = trim($input['cds_host']);
	$newinput['cds_domain'] = trim($input['cds_domain']);

	// TODO: Validate the data in $newinput to make sure it looks like a hostname and doesn't have a protocol

	return $newinput;
}

function cds_integration_main_settings_text() {
	echo '<p>Enter the information provided by Catalog Data Solutions for your site into the fields in this section.</p>';
}

function cds_integration_host_setting() {
	$options = get_option('cds_integration_settings');

	echo '<input id="cds_integration_host" name="cds_integration_settings[cds_host]" size="60" type="text" value="'
		. $options['cds_host'] . '">';
}

function cds_integration_domain_setting() {
	$options = get_option('cds_integration_settings');

	echo '<input id="cds_integration_domain" name="cds_integration_settings[cds_domain]" size="60" type="text" value="'
		. $options['cds_domain'] . '">';
}
