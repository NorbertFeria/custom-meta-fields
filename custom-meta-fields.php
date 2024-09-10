<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://norbertferia.com
 * @since             2.0.0
 * @package           Custom_Meta_Fields
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Meta fields
 * Plugin URI:        https://norbertferia.com/plugins/custom-meta-fields/
 * Description:       Custom Metabox v 2.0 plugin enables you to create dynamic metabox and metabox fields from the dashboard. This plugin uses New data system.
 * Version:           2.0.0
 * Author:            Norbert Feria
 * Author URI:        https://norbertferia.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       custom-meta-fields
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 2.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CUSTOM_META_FIELDS_VERSION', '2.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-custom-meta-fields-activator.php
 */
function activate_custom_meta_fields() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-meta-fields-activator.php';
	Custom_Meta_Fields_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-custom-meta-fields-deactivator.php
 */
function deactivate_custom_meta_fields() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-meta-fields-deactivator.php';
	Custom_Meta_Fields_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_custom_meta_fields' );
register_deactivation_hook( __FILE__, 'deactivate_custom_meta_fields' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-custom-meta-fields.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
require_once( 'plugin_registry.php' );
$registry = Plugin_Registry::get_instance();
$registry->add( 'custom_meta_fields', new Custom_Meta_Fields() );
$registry->get( 'custom_meta_fields' )->run();


function get_the_cmf_field( $field_name = NULL, $post = NULL ){

	if( is_null( $field_name ) ){
		return NULL;
	}

	$registry = Plugin_Registry::get_instance();
    
	$custom_meta_field = $registry->get( 'custom_meta_fields' );
	
	$field_value = $custom_meta_field->public->get_the_custom_meta_field( $field_name, $post );

	return $field_value;
}

function the_cmf_field( $field_name = NULL, $post = NULL ){

	if( is_null( $field_name ) ){
		return NULL;
	}
	$registry = Plugin_Registry::get_instance();
	$custom_meta_field = $registry->get( 'custom_meta_fields' );

	$field_value = $custom_meta_field->public->get_the_custom_meta_field( $field_name, $post );

	echo $field_value;
}