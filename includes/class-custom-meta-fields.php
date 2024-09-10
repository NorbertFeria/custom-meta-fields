<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://norbertferia.com
 * @since      2.0.0
 *
 * @package    Custom_Meta_Fields
 * @subpackage Custom_Meta_Fields/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0.0
 * @package    Custom_Meta_Fields
 * @subpackage Custom_Meta_Fields/includes
 * @author     Norbert Feria <norbert.feria@gmail.com>
 */
class Custom_Meta_Fields {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      Custom_Meta_Fields_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The label of this plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $plugin_label    The string used as a label of the plugin.
	 */
	protected $plugin_label;

	/**
	 * The current version of the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Store plugin public class to allow public access.
	 *
	 * @since    2.0.0
	 * @var object      The admin class.
	 */
	public $public;

	/**
	 * 
	 * Store plugin main class to allow public access.
	 *
	 *@since    2.0.0
	 * @var object      The main class.
	 */
	public $main;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function __construct() {
		$this->main = $this;
		if ( defined( 'CUSTOM_META_FIELDS_VERSION' ) ) {
			$this->version = CUSTOM_META_FIELDS_VERSION;
		} else {
			$this->version = '2.0.0';
		}
		$this->plugin_name  = 'custom-meta-fields';
		$this->plugin_label = 'Custom meta fields';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->register_post_types();
	}


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Custom_Meta_Fields_Loader. Orchestrates the hooks of the plugin.
	 * - Custom_Meta_Fields_i18n. Defines internationalization functionality.
	 * - Custom_Meta_Fields_Admin. Defines all hooks for the admin area.
	 * - Custom_Meta_Fields_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-custom-meta-fields-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-custom-meta-fields-i18n.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-custom-meta-fields-post-types.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-custom-meta-fields-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-custom-meta-fields-public.php';

		$this->loader = new Custom_Meta_Fields_Loader();
	}

	/**
	 * Define custom post types and custom meta fields used by the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function register_post_types() {

		$plugin_post_types = new Custom_Meta_Fields_Post_Types();

		$this->loader->add_action( 'init', $plugin_post_types, 'register_meta_box_cpt' );
		$this->loader->add_action( 'init', $plugin_post_types, 'register_target_post_field' );
		$this->loader->add_action( 'init', $plugin_post_types, 'register_meta_field_cpt' );
		$this->loader->add_action( 'init', $plugin_post_types, 'register_meta_field_type' );

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Custom_Meta_Fields_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Custom_Meta_Fields_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Custom_Meta_Fields_Admin( $this->get_plugin_label(), $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		$this->loader->add_action( 'admin_meta_form_action', $plugin_admin, 'custom_meta_fields_form_actions' );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'register_custom_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_meta_box_data' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$this->public = new Custom_Meta_Fields_Public( $this->get_plugin_name(), $this->get_version(), $this->main );

		$this->loader->add_action( 'wp_enqueue_scripts', $this->public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this->public, 'enqueue_scripts' );
		$this->loader->add_shortcode( "cmf_field", $this->public, "the_cmf_shortcode_function", $priority = 10, $accepted_args = 2 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    2.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     2.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The label of the plugin used to uniquely label the plugin on the admin menu.
	 *
	 * @since     2.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_label() {
		return $this->plugin_label;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     2.0.0
	 * @return    Custom_Meta_Fields_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     2.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
