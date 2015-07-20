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

	add_settings_section('cds_integration_environments', 'Environments', 'cds_integration_environments_text', 'cds-integration');
	add_settings_field('cds_environments', 'CDS Environments', 'cds_integration_environments_setting', 'cds-integration', 'cds_integration_environments');
}

function cds_integration_settings_validate($input) {
	$newinput = array();

	$newinput['cds_host'] = trim($input['cds_host']);
	$newinput['cds_domain'] = trim($input['cds_domain']);


	$environments = array();

	if (!empty(trim($input['cds_environments']))) {
		foreach (explode("\n", trim($input['cds_environments'])) as $environmentDefinition) {
			list($categoryId, $basePath) = explode('|', $environmentDefinition);

			if (empty($categoryId) || empty($basePath)) {
				continue;
			}

			$environments[$basePath] = $categoryId;
		}
	}

	$newinput['cds_environments'] = $environments;

	// TODO: Validate $newinput['host'] to make sure it looks like a hostname and strip any protocol

	return $newinput;
}

function cds_integration_main_settings_text() {
	echo '<p>Enter the information provided by Catalog Data Solutions for your site into the fields in this section.</p>';
}

function cds_integration_environments_text() {
	echo '<p>Enter the path and top category ID for each environment you would like, one per line, in the format
			"category_id|base-path." For example:</p>';
	echo '<blockquote>widgets|path/to/landing/page</blockquote>';
	echo '<p>The above environment would show the "widgets" category and its related facets at the path/to/landing/page path.</p>';
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

function cds_integration_environments_setting() {
	$options = get_option('cds_integration_settings');

	$environments = $options['cds_environments'];

	$default = '';

	foreach ($environments as $basePath => $categoryId) {
		$default .= "$categoryId|$basePath\n";
	}

	echo '<textarea id="cds_integration_environments" name="cds_integration_settings[cds_environments]" rows="5" cols="60">' . $default . '</textarea>';
}