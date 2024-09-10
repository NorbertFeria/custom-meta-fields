<?php
/**
 * Provide admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://norbertferia.com
 * @since      2.0.0
 *
 * @package    Custom_Meta_Fields
 * @subpackage Custom_Meta_Fields/admin/page
 */

	// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

	do_action( 'admin_meta_form_action' );

	$plugin_slug = 'custom-meta-fields';
	$admin_url   = admin_url( 'admin.php?page=' . $plugin_slug )
?>

	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Custom Meta Fields Setup', 'custom-meta-fields' ); ?></h1>

		<!-- Admin page tabs. -->
		<h2 class="nav-tab-wrapper">
			<a href="#existing-meta-boxes" class="nav-tab nav-tab-active"><?php esc_html_e( 'Meta Boxes', 'custom-meta-fields' ); ?></a>        
			<a href="#existing-meta-fields" class="nav-tab"><?php esc_html_e( 'Meta Fields', 'custom-meta-fields' ); ?></a>		
		</h2>	
		<?php require_once 'partials/existing-meta-boxes-tab.php'; ?>
		<?php require_once 'partials/add-meta-box-tab.php'; ?>
		<?php require_once 'partials/existing-meta-fields-tab.php'; ?>
		<?php require_once 'partials/add-meta-field-tab.php'; ?>
		<?php echo '<div class="tablenav bottom"><div class="tablenav-pages one-page">' . esc_html( $this->plugin_label ) . ' ' . esc_html( $this->version ) . '</div></div>'; ?>
	</div>
