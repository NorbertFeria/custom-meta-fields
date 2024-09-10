<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://norbertferia.com
 * @since      2.0.0
 *
 * @package    Custom_Meta_Fields
 * @subpackage Custom_Meta_Fields/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Custom_Meta_Fields
 * @subpackage Custom_Meta_Fields/admin
 * @author     Norbert Feria <norbert.feria@gmail.com>
 */
class Custom_Meta_Fields_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The label of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_label    The Label of this plugin.
	 */
	private $plugin_label;
	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      object    post object that is being edited
	 */
	private $edit_data;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param      string $label_name    The name of this plugin.
	 * @param      string $plugin_name       The name or slug of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $label_name, $plugin_name, $version ) {

		$this->plugin_name  = $plugin_name;
		$this->plugin_label = $label_name;
		$this->version      = $version;

	}

	/**
	 * Define the plugin admin menu.
	 *
	 * @since    2.0.0
	 */
	public function add_plugin_admin_menu() {
		add_submenu_page(
			'edit.php',
			__( 'Custom Meta Fields', 'custom-meta-fields' ),
			__( 'Custom Meta Fields', 'custom-meta-fields' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_plugin_admin_page' )
		);
	}

	/**
	 * Handles admin page for the plugin.
	 *
	 * @return void
	 */
	public function display_plugin_admin_page() {
		include_once 'page/admin-page.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/custom-meta-fields-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/custom-meta-fields-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Handles custom meta box and meta fields form actions.
	 *
	 * @return void
	 */
	public function custom_meta_fields_form_actions() {
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && ( 'POST' === $_SERVER['REQUEST_METHOD'] ) ) {

			if ( isset( $_POST['context'] ) && ( 'meta_box' === $_POST['context'] ) ) {
				if ( isset( $_POST['action'] ) ) {
					switch ( $_POST['action'] ) {
						case 'toggle_status':
							$nonce = isset( $_POST['meta_box_toggle_status_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['meta_box_toggle_status_nonce'] ) ) : '';
							if ( ! $nonce || ! wp_verify_nonce( $nonce, 'meta_box_toggle_status' ) ) {
								die( 'Nonce verification failed' );
							}

							if ( isset( $_POST['post_id'] ) ) {
								$post_id = intval( $_POST['post_id'] );
							}
							$current_status = get_post_status( $post_id );

							$new_status = ( 'publish' === $current_status ) ? 'draft' : 'publish';

							wp_update_post(
								array(
									'ID'          => $post_id,
									'post_status' => $new_status,
								)
							);

							$action_taken = ( 'publish' === $new_status ) ? 'Activated' : 'Deactivated';

							echo '<div class="notice notice-success is-dismissible"><p>Meta Box ' . esc_html( $action_taken ) . '</p></div>';

							break;

						case 'edit':
							$nonce = isset( $_POST['meta_box_edit_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['meta_box_edit_nonce'] ) ) : '';
							if ( ! $nonce || ! wp_verify_nonce( $nonce, 'meta_box_edit' ) ) {
								die( 'Nonce verification failed' );
							}
							if ( isset( $_POST['post_id'] ) ) {
								$this->edit_data = get_post( intval( $_POST['post_id'] ) );
							}

							break;

						case 'delete':
							$nonce = isset( $_POST['meta_box_delete_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['meta_box_delete_nonce'] ) ) : '';
							if ( ! $nonce || ! wp_verify_nonce( $nonce, 'meta_box_delete' ) ) {
								die( 'Nonce verification failed' );
							}
							if ( isset( $_POST['post_id'] ) ) {
								wp_delete_post( intval( $_POST['post_id'] ) );
							}

							echo '<div class="notice notice-success is-dismissible"><p>Meta Box deleted successfully.</p></div>';

							break;

						case 'save':
							$nonce = isset( $_POST['meta_box_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['meta_box_nonce'] ) ) : '';
							if ( ! $nonce || ! wp_verify_nonce( $nonce, 'meta_box_form' ) ) {
								die( 'Nonce verification failed' );
							}

							$post_data = array(
								'post_title'  => $this->get_sanitized_field( 'meta_box_title', 'No title' ),
								'post_type'   => 'cmf_meta_box',
								'post_status' => 'publish',
							);

							$post_id = wp_insert_post( $post_data );

							if ( $post_id ) {
								update_post_meta( $post_id, 'cmf_target_post', $this->get_sanitized_field( 'target_post' ) );
							}

							echo '<div class="notice notice-success is-dismissible"><p>Meta Box saved successfully.</p></div>';
							break;

						case 'update':
							$nonce = isset( $_POST['meta_box_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['meta_box_nonce'] ) ) : '';
							if ( ! $nonce || ! wp_verify_nonce( $nonce, 'meta_box_form' ) ) {
								die( 'Nonce verification failed' );
							}
							if ( isset( $_POST['post_id'] ) ) {
								$post_id = intval( $_POST['post_id'] );
							}

							$post_data = array(
								'post_title'  => $this->get_sanitized_field( 'meta_box_title', 'No title' ),
								'post_type'   => 'cmf_meta_box',
								'post_status' => 'publish',
							);

							$post_data['ID'] = $post_id;

							wp_update_post( $post_data );

							if ( $post_id ) {
								update_post_meta( $post_id, 'cmf_target_post', $this->get_sanitized_field( 'target_post' ) );
							}

							echo '<div class="notice notice-success is-dismissible"><p>Meta Box updated successfully.</p></div>';
							break;

						default:
					}
				}
			}

			if ( isset( $_POST['context'] ) && ( 'meta_field' === $_POST['context'] ) ) {
				if ( isset( $_POST['action'] ) ) {
					switch ( $_POST['action'] ) {
						case 'toggle_status':
							$nonce = isset( $_POST['meta_field_toggle_status_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['meta_field_toggle_status_nonce'] ) ) : '';
							if ( ! $nonce || ! wp_verify_nonce( $nonce, 'meta_field_toggle_status' ) ) {
								die( 'Nonce verification failed' );
							}

							$post_id        = intval( $_POST['post_id'] );
							$current_status = get_post_status( $post_id );

							// Toggle the status.
							$new_status = ( 'publish' === $current_status ) ? 'draft' : 'publish';

							// Update the post status.
							wp_update_post(
								array(
									'ID'          => $post_id,
									'post_status' => $new_status,
								)
							);

							$action_taken = ( 'publish' === $new_status ) ? 'Activated' : 'Deactivated';

							echo '<div class="notice notice-success is-dismissible"><p>Meta Field ' . esc_html( $action_taken ) . '</p></div>';
							break;
						case 'update':
							$nonce = isset( $_POST['meta_field_form_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['meta_field_form_nonce'] ) ) : '';
							if ( ! $nonce || ! wp_verify_nonce( $nonce, 'meta_field_form' ) ) {
								die( 'Nonce verification failed' );
							}

							if ( isset( $_POST['parent_meta_box'] ) ) {
								$parent_meta_box_id = intval( $_POST['parent_meta_box'] );
							}

							if ( isset( $_POST['post_id'] ) ) {
								$post_id = intval( $_POST['post_id'] );
							}

							wp_update_post(
								array(
									'ID'          => $post_id,
									'post_title'  => $this->get_sanitized_field( 'meta_field_title', 'No title' ),
									'post_parent' => $parent_meta_box_id,
									'post_type'   => 'cmf_meta_field',
									'post_status' => 'publish',
								)
							);

							update_post_meta( $post_id, 'cmf_field_type', $this->get_sanitized_field( 'field_type', 'Text' ) );

							$choices_array = array_filter( array_map( 'trim', explode( "\n", $this->get_sanitized_field( 'field_choices' ) ) ) );

							$serialized_choices = maybe_serialize( $choices_array );

							update_post_meta( $post_id, 'cmf_field_choices', $serialized_choices );

							echo '<div class="notice notice-success is-dismissible"><p>Meta Field updated successfully.</p></div>';
							break;
						case 'save':
							$nonce = isset( $_POST['meta_field_form_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['meta_field_form_nonce'] ) ) : '';
							if ( ! $nonce || ! wp_verify_nonce( $nonce, 'meta_field_form' ) ) {
								die( 'Nonce verification failed' );
							}

							$parent_meta_box_id = intval( $_POST['parent_meta_box'] );

							$post_id = wp_insert_post(
								array(
									'post_title'  => $this->get_sanitized_field( 'meta_field_title', 'No title' ),
									'post_parent' => $parent_meta_box_id,
									'post_type'   => 'cmf_meta_field',
									'post_status' => 'publish',
								)
							);

							update_post_meta( $post_id, 'cmf_field_type', $this->get_sanitized_field( 'field_type' ) );

							$choices_array      = array_filter( array_map( 'trim', explode( "\n", $this->get_sanitized_field( 'field_choices' ) ) ) );
							$serialized_choices = maybe_serialize( $choices_array );

							update_post_meta( $post_id, 'cmf_field_choices', $serialized_choices );

							echo '<div class="notice notice-success is-dismissible"><p>Meta Field saved successfully.</p></div>';
							break;

						case 'edit':
							$nonce = isset( $_POST['meta_field_edit_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['meta_field_edit_nonce'] ) ) : '';
							if ( ! $nonce || ! wp_verify_nonce( $nonce, 'meta_field_edit' ) ) {
								die( 'Nonce verification failed' );
							}
							if ( isset( $_POST['post_id'] ) ) {
								$this->edit_data = get_post( intval( $_POST['post_id'] ) );
							}

							break;
						case 'delete':
							$nonce = isset( $_POST['meta_field_delete_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['meta_field_delete_nonce'] ) ) : '';
							if ( ! $nonce || ! wp_verify_nonce( $nonce, 'meta_field_delete' ) ) {
								die( 'Nonce verification failed' );
							}
							if ( isset( $_POST['post_id'] ) ) {
								wp_delete_post( intval( $_POST['post_id'] ) );
							}

							echo '<div class="notice notice-success is-dismissible"><p>Meta Field deleted successfully.</p></div>';
							break;
						default:
					}
				}
			}
		}
	}

	/**
	 * Handles the sanitation of data.
	 *
	 * @since    2.0.0
	 * @param string $field_name string the name of the field to sanitize.
	 * @param string $default The return variable default NULL.
	 * @param string $method The method of request being sanitized default post.
	 *
	 * @return string The sanitized value or the default parameter.
	 */
	private function get_sanitized_field( $field_name, $default = null, $method = 'post' ) {
		// phpcs:disable 
		if ( 'post' === $method ) {
			if ( isset( $_POST[ $field_name ] ) ) {
				if( is_array( $_POST[ $field_name ] ) ){
					return array_map( 'sanitize_text_field', $_POST[ $field_name ] );
				}else{
					return sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) );
				}
			}
		} else {
			if ( isset( $_GET[ $field_name ] ) ) {
				return sanitize_text_field( wp_unslash( $_GET[ $field_name ] ) );
			}
		}
		// phpcs:enable 
		return $default;
	}

	/**
	 * Handles addition of the custom meta boxes from the data stored.
	 *
	 * @param string $post_type The post type.
	 *
	 * @return void
	 */
	public function register_custom_boxes( $post_type ) {

		$meta_boxes = get_posts(
			array(
				'post_type'      => 'cmf_meta_box',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			)
		);

		foreach ( $meta_boxes as $meta_box ) {
			$target_post = get_post_meta( $meta_box->ID, 'cmf_target_post', true );
			if ( $post_type === $target_post ) {
				add_meta_box(
					'mb_' . $meta_box->ID,
					$meta_box->post_title,
					array( $this, 'render_meta_box_callback' ),
					$target_post,
					'advanced',
					'high',
					array( 'parent_meta_box_id' => $meta_box->ID )
				);
			}
		}

	}

	/**
	 * Handles rendering of the meta boxes added.
	 *
	 * @param string $post object The current post.
	 * @param string $args Custom arguments from adding the meta box.
	 *
	 * @return void
	 */
	public function render_meta_box_callback( $post, $args ) {

		$parent_meta_box_id = $args['args']['parent_meta_box_id'];

		$meta_fields = get_posts(
			array(
				'post_type'      => 'cmf_meta_field',
				'posts_per_page' => -1,
				'post_parent'    => $parent_meta_box_id,
				'post_status'    => 'publish',
			)
		);

		wp_nonce_field( 'save_meta_box_data', 'meta_box_data_nonce' );
		echo '<div class="cmf-box">';
		foreach ( $meta_fields as $meta_field ) {

			$field_value = get_post_meta( $post->ID, $meta_field->post_name, true );
			$field_type  = get_post_meta( $meta_field->ID, 'cmf_field_type', true );

			if ( ( strtolower( $field_type ) === strtolower( 'text' ) ) ||
				( strtolower( $field_type ) === strtolower( 'Number' ) ) ||
				( strtolower( $field_type ) === strtolower( 'Email' ) ) ||
				( strtolower( $field_type ) === strtolower( 'URL' ) ) ||
				( strtolower( $field_type ) === strtolower( 'Password' ) ) ||
				( strtolower( $field_type ) === strtolower( 'Color' ) ) ||
				( strtolower( $field_type ) === strtolower( 'Number' ) ) ||
				( strtolower( $field_type ) === strtolower( 'Time' ) ) ||
				( strtolower( $field_type ) === strtolower( 'Date' ) )
			) {
				echo $this->before_field_html( $meta_field->ID ) . $this->get_input_field_render( $meta_field, $field_value, strtolower( $field_type ) ) . $this->after_field_html();
			} else {
				if ( ( strtolower( $field_type ) === strtolower( 'dropdown' ) ) ||
					( strtolower( $field_type ) === strtolower( 'radio' ) ) ||
					( strtolower( $field_type ) === strtolower( 'checkbox' ) )
				) {
					$field_choices = maybe_unserialize( get_post_meta( $meta_field->ID, 'cmf_field_choices', true ) );

					if ( strtolower( $field_type ) === strtolower( 'dropdown' ) ) {
						echo $this->before_field_html( $meta_field->ID ) . $this->get_dropdown_field_render( $meta_field, $field_choices, $field_value ) . $this->after_field_html();
					}

					if ( strtolower( $field_type ) === strtolower( 'radio' ) ) {
						echo $this->before_field_html( $meta_field->ID ) . $this->get_radio_field_render( $meta_field, $field_choices, $field_value ) . $this->after_field_html();
					}

					if ( strtolower( $field_type ) === strtolower( 'checkbox' ) ) {
						echo $this->before_field_html( $meta_field->ID ) . $this->get_check_field_render( $meta_field, $field_choices, $field_value ) . $this->after_field_html();
					}
				}
				if ( strtolower( $field_type ) === strtolower( 'Textarea' ) ) {
					echo $this->before_field_html( $meta_field->ID ) . $this->get_textarea_field_render( $meta_field, $field_value ) . $this->after_field_html();
				}
				if ( strtolower( $field_type ) === strtolower( 'Array' ) ) {
					echo $this->before_field_html( $meta_field->ID ) . $this->get_array_fields_render( $meta_field, $post->ID ) . $this->after_field_html();
				}
			}
		}
		echo '<input type="hidden" id="meta_box_id" name="meta_box_id[]"  value="' . esc_html( $parent_meta_box_id ) . '">';
		echo '</div>';
	}

	/**
	 * Handles the saving of data from the custom meta fields.
	 *
	 * @param string $post_id The post ID of the post currently being saved.
	 *
	 * @return void
	 */
	public function save_meta_box_data( $post_id ) {
		// Verify nonce.
		$nonce = isset( $_POST['meta_box_data_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['meta_box_data_nonce'] ) ) : '';
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'save_meta_box_data' ) ) {
			return;
		}

		// Check for autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check user permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['meta_box_id'] ) ) {
			foreach ( $this->get_sanitized_field( 'meta_box_id' ) as $meta_box_id ) {

				$meta_fields = get_posts(
					array(
						'post_type'      => 'cmf_meta_field',
						'posts_per_page' => -1,
						'post_parent'    => $meta_box_id,
						'post_status'    => 'publish',
					)
				);

				foreach ( $meta_fields as $meta_field ) {
					$field_type = get_post_meta( $meta_field->ID, 'cmf_field_type', true );
					$fld_value  = $this->get_sanitized_field( $meta_field->post_name );

					if ( isset( $_POST[ $meta_field->post_name ] ) ) {
						switch ( strtolower( $field_type ) ) {
							case strtolower( 'checkbox' ):
								$field_choices = (array) $this->get_sanitized_field( $meta_field->post_name );
								$field_choices = array_map( 'sanitize_text_field', $field_choices );
								update_post_meta( $post_id, $meta_field->post_name, maybe_serialize( $field_choices ) );
								break;
							case strtolower( 'array' ):
								if ( is_array( $_POST[ $meta_field->post_name ] ) ) {
									$custom_fields = array_map( 'sanitize_text_field', $this->get_sanitized_field( $meta_field->post_name ) );
									if ( ! empty( $custom_fields ) ) {
										update_post_meta( $post_id, $meta_field->post_name, $custom_fields );
									}
								}
								break;
							case strtolower( 'radio' ):
								if ( is_array( $fld_value ) && count( $fld_value ) === 1 && ! empty( $fld_value[0] ) ) {
									$value = sanitize_text_field( $fld_value[0] );
									update_post_meta( $post_id, $meta_field->post_name, $value );
								}
								break;
							case strtolower( 'dropdown' ):
								update_post_meta( $post_id, $meta_field->post_name, sanitize_text_field( $fld_value ) );
								break;
							default:
								update_post_meta( $post_id, $meta_field->post_name, sanitize_text_field( $fld_value ) );
						}
					} else {
						delete_post_meta( $post_id, $meta_field->post_name );
					}
				}
			}
		}
	}

	/**
	 * Define the HTML before the field input render.
	 *
	 * @since    2.0.0
	 * @param integer $meta_field_id The id of the of the meta field.
	 * @return string The HTML to render before field input render.
	 */
	public function before_field_html( $meta_field_id ) {
		return '<div class="cmf-field cmf-field-' . $meta_field_id . '">';
	}

	/**
	 * Define the HTML after the field input render.
	 *
	 * @since    2.0.0
	 * @return string The HTML to render after field input render.
	 */
	public function after_field_html() {
		return '</div>';
	}

	/**
	 * Define the the field input render.
	 *
	 * @since    2.0.0
	 * @param string $meta_field Object for the array input field.
	 * @param string $field_value The current field value stored for the meta field.
	 * @param string $field_type The field type of the meta field.
	 *
	 * @return string The HTML to render the field input.
	 */
	public function get_input_field_render( $meta_field, $field_value, $field_type ) {

		$field_name  = $meta_field->post_name;
		$field_title = $meta_field->post_title;

		$input_field  = '<div class="cmf-label"><label for="' . esc_attr( $meta_field->post_name ) . '-' . $meta_field->ID . '">' . $meta_field->post_title . '</label></div>';
		$input_field .= '<input class="widefat" type="' . $field_type . '" id="' . esc_attr( $meta_field->post_name ) . '-' . $meta_field->ID . '" name="' . esc_attr( $meta_field->post_name ) . '" value="' . esc_attr( $field_value ) . '" />';

		return $input_field;
	}

	/**
	 * Define the the textarea field input render.
	 *
	 * @since    2.0.0
	 * @param string $meta_field Object for the array input field.
	 * @param string $field_value The current field value stored for the meta field.
	 *
	 * @return string The HTML to render the textarea field input.
	 */
	public function get_textarea_field_render( $meta_field, $field_value ) {

		$field_name  = $meta_field->post_name;
		$field_title = $meta_field->post_title;

		$textarea_field  = '<div class="cmf-label"><label for="' . esc_attr( $field_name ) . '-' . $meta_field->ID . '">' . $field_title . '</label></div>';
		$textarea_field .= '<textarea class="widefat" id="' . esc_attr( $field_name ) . '-' . $meta_field->ID . '" name="' . esc_attr( $field_name ) . '">' . esc_textarea( $field_value ) . '</textarea>';

		return $textarea_field;
	}

	/**
	 * Define the the dropdown field input render.
	 *
	 * @since    2.0.0
	 * @param string $meta_field Object for the array input field.
	 * @param string $field_choices The stored field choices.
	 * @param string $field_value The current field value stored for the meta field.
	 *
	 * @return string The HTML to render the dropdown field input.
	 */
	public function get_dropdown_field_render( $meta_field, $field_choices, $field_value ) {

		$field_name  = $meta_field->post_name;
		$field_title = $meta_field->post_title;

		$dropdown_field = '<div class="cmf-label"><label for="' . esc_attr( $field_name ) . '-' . $meta_field->ID . '">' . $field_title . '</label></div>';

		$dropdown_field .= '<select  class="widefat" id="' . esc_attr( $field_name ) . '-' . $meta_field->ID . '" name="' . esc_attr( $field_name ) . '">';
		foreach ( $field_choices as $choice ) {
			if ( strpos( $choice, ':' ) !== false ) {
				list($value, $label) = array_map( 'trim', explode( ':', $choice, 2 ) );
			} else {
				$value = trim( $choice );
				$label = $value;
			}

			$dropdown_field .= '<option value="' . esc_attr( $value ) . '" ' . selected( $field_value, $value, false ) . '>' . esc_html( $label ) . '</option>';
		}
		$dropdown_field .= '</select>';

		return $dropdown_field;
	}

	/**
	 * Define the the radio field input render.
	 *
	 * @since    2.0.0
	 * @param string $meta_field Object for the array input field.
	 * @param string $field_choices The stored field choices.
	 * @param string $field_value The current field value stored for the meta field.
	 *
	 * @return string The HTML to render the radio field input.
	 */
	public function get_radio_field_render( $meta_field, $field_choices, $field_value ) {

		$field_name  = $meta_field->post_name;
		$field_title = $meta_field->post_title;

		$radio_field = '<div class="cmf-label"><span>' . $field_title . '</span></div>';

		$radio_field .= '<label>';
		foreach ( $field_choices as $choice ) {
			if ( strpos( $choice, ':' ) !== false ) {
				list($value, $label) = array_map( 'trim', explode( ':', $choice, 2 ) );
			} else {
				$value = trim( $choice );
				$label = $value;
			}

			$radio_field .= '<input type="radio" name="' . esc_attr( $field_name ) . '[]" value="' . esc_attr( $value ) . '" ' . checked( $field_value, $value, false ) . '>' . esc_html( $label ) . '<Br />';
		}
		$radio_field .= '</label>';

		return $radio_field;
	}

	/**
	 * Define the the checkbox field input render.
	 *
	 * @since    2.0.0
	 * @param string $meta_field Object for the array input field.
	 * @param string $field_choices The stored field choices.
	 * @param string $field_value The current field value stored for the meta field.
	 *
	 * @return string The HTML to render the checkbox field input.
	 */
	public function get_check_field_render( $meta_field, $field_choices, $field_value ) {

		$field_name  = $meta_field->post_name;
		$field_title = $meta_field->post_title;

		$field_values = maybe_unserialize( $field_value );
		if ( ! is_array( $field_values ) ) {
			$field_values = array();
		}

		$check_field = '<div class="cmf-label"><span>' . $field_title . '</span></div>';

		$check_field .= '<label>';
		foreach ( $field_choices as $choice ) {
			if ( strpos( $choice, ':' ) !== false ) {
				list($value, $label) = array_map( 'trim', explode( ':', $choice, 2 ) );
			} else {
				$value = trim( $choice );
				$label = $value;
			}
			$checked = '';
			if ( in_array( $value, $field_values, true ) ) {
				$checked = 'checked="checked"';
			}
			$check_field .= '<input type="checkbox" name="' . esc_attr( $field_name ) . '[]" value="' . esc_attr( $value ) . '" ' . $checked . '>' . esc_html( $label ) . '<Br />';
		}
		$check_field .= '</label>';

		return $check_field;
	}

	/**
	 * Define the the array field input render.
	 *
	 * @since    2.0.0
	 * @param string $meta_field Object for the array input field.
	 * @param string $post_id The current post ID for the current post that is edited.
	 * @return string The HTML to render the array field input.
	 */
	public function get_array_fields_render( $meta_field, $post_id ) {

		$field_name  = $meta_field->post_name;
		$field_title = $meta_field->post_title;

		$field_values = maybe_unserialize( get_post_meta( $post_id, $field_name, true ) );

		if ( ! is_array( $field_values ) ) {
			$field_values = array();
		}

		$array_fields = '<div class="cmf-label"><span>' . $field_title . '</span></div>';

		$array_fields .= '<div id="array-fields-' . esc_attr( $field_name ) . '">';
		if ( ! empty( $field_values ) ) {
			foreach ( $field_values as $value ) {
				$array_fields .= '<p><input type="text" class="regular-text" name="' . esc_attr( $field_name ) . '[]" value="' . esc_attr( $value ) . '" />';
				$array_fields .= '<a class="' . esc_attr( $field_name ) . '-remove-field remove_item"><span class="dashicons dashicons-no"></span>Remove</a></p>';
			}
		} else {
			$array_fields .= '<p><input type="text" class="regular-text" name="' . esc_attr( $field_name ) . '[]" value="" />';
			$array_fields .= '<a class="' . esc_attr( $field_name ) . '-remove-field remove_item"><span class="dashicons dashicons-no"></span>Remove</a></p>';
		}
		$array_fields .= '</div>';
		$array_fields .= '<button id="' . esc_attr( $field_name ) . '-add-field" class="button button-small button-secondary"><span class="dashicons dashicons-plus"></span> Add Field</button>';

		// Render inline script to handle adding and removing fields.
		$array_fields .= "
			<script>
			jQuery(document).ready(function($) {
				$('#" . esc_attr( $field_name ) . "-add-field').click(function(e) {
					e.preventDefault();
					$('#array-fields-" . esc_attr( $field_name ) . "').append('<p><input type=\"text\" class=\"regular-text\" name=\"" . esc_attr( $field_name ) . '[]" value="" /><a class="' . esc_attr( $field_name ) . "-remove-field remove_item\"><span class=\"dashicons dashicons-no\"></span>Remove</a></p>');
				});
				$(document).on('click', '." . esc_attr( $field_name ) . "-remove-field', function(e) {
					e.preventDefault();
					$(this).parent('p').remove();
				});
			});
			</script>
		";
		return $array_fields;
	}

}
