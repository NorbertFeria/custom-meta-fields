<?php
/**
 * The file that defines custom post types used by the plugin.
 *
 * @link       https://norbertferia.com
 * @since      2.0.0
 *
 * @package    Custom_Meta_Fields
 * @subpackage Custom_Meta_Fields/includes
 */

/**
 * The plugin class that defines the custom post types and custom meta fields used by the plugin.
 *
 * @since      2.0.0
 * @package    Custom_Meta_Fields
 * @subpackage Custom_Meta_Fields/includes
 * @author     Norbert Feria <norbert.feria@gmail.com>
 */
class Custom_Meta_Fields_Post_Types {

	/**
	 * The construct of the class.
	 *
	 * @since    2.0.0
	 */
	public function __construct() {
	}

	/**
	 * Function that defines and registers the cmf_meta_box post type that the plugin uses.
	 *
	 * @since    2.0.0
	 */
	public function register_meta_box_cpt() {
		$labels = array(
			'name'               => _x( 'Meta Boxes', 'Post Type General Name', 'custom-meta-fields' ),
			'singular_name'      => _x( 'Meta Box', 'Post Type Singular Name', 'custom-meta-fields' ),
			'menu_name'          => _x( 'Meta Boxes', 'Admin Menu text', 'custom-meta-fields' ),
			'name_admin_bar'     => _x( 'Meta Box', 'Add New on Toolbar', 'custom-meta-fields' ),
			'add_new_item'       => __( 'Add New Meta Box', 'custom-meta-fields' ),
			'new_item'           => __( 'New Meta Box', 'custom-meta-fields' ),
			'edit_item'          => __( 'Edit Meta Box', 'custom-meta-fields' ),
			'view_item'          => __( 'View Meta Box', 'custom-meta-fields' ),
			'all_items'          => __( 'All Meta Boxes', 'custom-meta-fields' ),
			'search_items'       => __( 'Search Meta Boxes', 'custom-meta-fields' ),
			'not_found'          => __( 'No meta boxes found.', 'custom-meta-fields' ),
			'not_found_in_trash' => __( 'No meta boxes found in Trash.', 'custom-meta-fields' ),
		);

		$args = array(
			'labels'          => $labels,
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => false,
			'query_var'       => true,
			'rewrite'         => array( 'slug' => 'meta-box' ),
			'capability_type' => 'post',
			'has_archive'     => false,
			'hierarchical'    => false,
			'menu_position'   => 20,
			'supports'        => array( 'title' ),
			'show_in_rest'    => true,
		);

		register_post_type( 'cmf_meta_box', $args );

	}

	/**
	 * Function that defines and registers target post meta field.
	 *
	 * @since    2.0.0
	 */
	public function register_target_post_field() {
		register_post_meta(
			'cmf_meta_box',
			'cmf_target_post',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	/**
	 * Function that defines and registers the cmf_meta_field post type that the plugin uses.
	 *
	 * @since    2.0.0
	 */
	public function register_meta_field_cpt() {
		$labels = array(
			'name'               => _x( 'Meta Fields', 'Post Type General Name', 'custom-meta-fields' ),
			'singular_name'      => _x( 'Meta Field', 'Post Type Singular Name', 'custom-meta-fields' ),
			'menu_name'          => _x( 'Meta Fields', 'Admin Menu text', 'custom-meta-fields' ),
			'name_admin_bar'     => _x( 'Meta Field', 'Add New on Toolbar', 'custom-meta-fields' ),
			'add_new_item'       => __( 'Add New Meta Field', 'custom-meta-fields' ),
			'new_item'           => __( 'New Meta Field', 'custom-meta-fields' ),
			'edit_item'          => __( 'Edit Meta Field', 'custom-meta-fields' ),
			'view_item'          => __( 'View Meta Field', 'custom-meta-fields' ),
			'all_items'          => __( 'All Meta Fields', 'custom-meta-fields' ),
			'search_items'       => __( 'Search Meta Fields', 'custom-meta-fields' ),
			'not_found'          => __( 'No meta fields found.', 'custom-meta-fields' ),
			'not_found_in_trash' => __( 'No meta fields found in Trash.', 'custom-meta-fields' ),
		);

		$args = array(
			'labels'          => $labels,
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => false,
			'query_var'       => true,
			'rewrite'         => array( 'slug' => 'meta-field' ),
			'capability_type' => 'post',
			'has_archive'     => false,
			'hierarchical'    => true,
			'menu_position'   => 21,
			'supports'        => array( 'title' ),
			'show_in_rest'    => true,
		);

		register_post_type( 'cmf_meta_field', $args );
	}

	/**
	 * Function that defines and registers field tu[e meta field.
	 *
	 * @since    2.0.0
	 */
	public function register_meta_field_type() {
		register_post_meta(
			'cmf_meta_field',
			'cmf_field_type',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	/**
	 * Function that defines and registers field choices meta field.
	 *
	 * @since    2.0.0
	 */
	public function register_meta_field_choices() {
		register_post_meta(
			'cmf_meta_field',
			'cmf_field_choices',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

}
