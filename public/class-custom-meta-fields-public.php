<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://norbertferia.com
 * @since      2.0.0
 *
 * @package    Custom_Meta_Fields
 * @subpackage Custom_Meta_Fields/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 *
 * @package    Custom_Meta_Fields
 * @subpackage Custom_Meta_Fields/public
 * @author     Norbert Feria <norbert.feria@gmail.com>
 */
class Custom_Meta_Fields_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Store plugin main class to allow public access.
	 *
	 *@since    2.0.0
	 * @var object      The main class.
	 */
	public $main;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_main ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->main = $plugin_main;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/custom-meta-fields-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/custom-meta-fields-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Function to get the value of a the named meta field.
	 *
	 * @since    2.0.0
	 * @param      string $field_name  The name of the field.
	 * @param      object|int $post    Post ID or post object. Default is the global $post.
	 */
	public function get_the_custom_meta_field( $field_name, $post = null ){

		if ( is_null( $post ) ) {
			global $post;
		} elseif ( is_numeric( $post ) ) {
			$post = get_post( $post );
		}

		if ( ! $post instanceof WP_Post ) {
			return false;
		}
		do_action( 'before_get_the_cmf_field', $field_name );

		$field_value = maybe_unserialize( get_post_meta( $post->ID, $field_name, true ) );
		
		return $field_value;

		do_action( 'after_get_the_cmf_field', $field_name );
	}

	/**
	 * Function to display the value of a the named meta field.
	 *
	 * @since    2.0.0
	 * @param      string $field_name  The name of the field.
	 * @param      object|int $post    Post ID or post object. Default is the global $post.
	 */
	public function the_custom_meta_field( $field_name, $post = null, $display = true ){

		$cmf_field_str = $this->get_the_cmf_field( $field_name, $post );

		do_action( 'before_the_cmf_field_display', $field_name );

		echo esc_html( $cmf_field_str );

		do_action( 'after_the_cmf_field_display', $field_name );
		
	}

	/**
	 * The cmf_field shortcode call back function.
	 *
	 * @since    2.0.0
	 * Sample usage: [cmf_field field_name="field-name"]
	 */
	public function the_cmf_shortcode_function( $atts ){
		$atts = shortcode_atts( 
			array (
			'field_name'  => '',
			'post_id'   => 0,
			), 
			$atts 
		);

		if( 0 === $atts['post_id']){
			$atts['post_id'] = NULL;
		}

		$field_value = $this->get_the_custom_meta_field( $atts['field_name'], $atts['post_id'] );
		
		return $field_value;
	}

}
