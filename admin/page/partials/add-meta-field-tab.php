<?php
/**
 * Provide add meta field tab on plugin admin.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://norbertferia.com
 * @since      2.0.0
 *
 * @package    Custom_Meta_Fields
 * @subpackage Custom_Meta_Fields/admin/page/partials
 */

?>
<div id="add-meta-field" class="tab-content" style="display:none;">
		<?php
		if ( $this->edit_data ) {
			$form_action        = 'update';
			$post_id_input      = '<input type="hidden" name="post_id" value="' . $this->edit_data->ID . '">';
			$meta_field_title   = esc_attr( $this->edit_data->post_title );
			$meta_field_type    = get_post_meta( $this->edit_data->ID, 'cmf_field_type', true );
			$parent_meta_box_id = $this->edit_data->post_parent;
			$submit_label       = __( 'Update Meta Field', 'custom-meta-fields' );
			$form_title         = __( 'Edit Field', 'custom-meta-fields' );
			$field_choices      = maybe_unserialize( get_post_meta( $this->edit_data->ID, 'cmf_field_choices', true ) );
		} else {
			$form_action        = 'save';
			$post_id_input      = '';
			$meta_field_title   = '';
			$parent_meta_box_id = 0;
			$meta_field_type    = '';
			$submit_label       = __( 'Save Field', 'custom-meta-fields' );
			$form_title         = __( 'Add New Field', 'custom-meta-fields' );
			$field_choices      = '';
		}
		?>
		<div class="postbox postbox-meta">
			<div class="postbox-header">
				<h2 class="postbox-meta-header"><?php echo esc_html( $form_title ); ?></h2>
				<?php if ( $this->edit_data ) : ?>
					<div class="postbox-meta-header-btns">
						<a href="<?php echo esc_html( admin_url( 'admin.php?page=custom-meta-fields&tab=add-meta-field' ) ); ?>" class="button postbox-meta-header-btn">Add new Meta field</a>
					</div>
				<?php endif; ?>
			</div>
			<form id="meta-field-form" method="post" action="<?php echo esc_html( $admin_url ); ?>&tab=existing-meta-fields">
				<div class="inside">
					<?php wp_nonce_field( 'meta_field_form', 'meta_field_form_nonce' ); ?>

					<input type="hidden" name="action" value="<?php echo esc_html( $form_action ); ?>">
					<input type="hidden" name="context" value="meta_field">
					<?php echo $post_id_input; ?>

					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="meta_field_title"><?php esc_html_e( 'Field Label', 'custom-meta-fields' ); ?></label>
							</th>
							<td>
								<input type="text" id="meta_field_title" name="meta_field_title" value="<?php echo esc_attr( $meta_field_title ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="parent_meta_box"><?php esc_html_e( 'Meta Box', 'custom-meta-fields' ); ?></label>
							</th>
							<td>
								<select id="parent_meta_box" name="parent_meta_box">
									<option value="0"><?php esc_html_e( 'Select a Meta Box', 'custom-meta-fields' ); ?></option>
									<?php
									$meta_boxes = get_posts(
										array(
											'post_type'   => 'cmf_meta_box',
											'numberposts' => -1,
											'post_status' => 'publish',
										)
									);
									foreach ( $meta_boxes as $meta_box ) {
										$target_post = get_post_meta( $meta_box->ID, 'cmf_target_post', true );
										echo '<option value="' . esc_attr( $meta_box->ID ) . '" ' . selected( $meta_box->ID, $parent_meta_box_id, false ) . '>' . esc_html( $meta_box->post_title ) . '-' . esc_html( $target_post ) . '</option>';
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="field_type"><?php esc_html_e( 'Field Type', 'custom-meta-fields' ); ?></label>
							</th>
							<td>
								<select id="field_type" name="field_type">
									<option value="0"><?php esc_html_e( 'Select the type of field', 'custom-meta-fields' ); ?></option>
									<?php
									$field_types = array(
										'Text'     => 'Text',
										'Textarea' => 'Textarea',
										'Array'    => 'Array',
										'Date'     => 'Date',
										'Number'   => 'Number',
										'Email'    => 'Email',
										'URL'      => 'URL',
										'Time'     => 'Time',
										'Password' => 'Password',
										'Color'    => 'Color',
										'Dropdown' => 'Dropdown',
										'Radio'    => 'Radio',
										'Checkbox' => 'Checkbox',
									);
									foreach ( $field_types as $key => $field_type ) {
										echo '<option value="' . esc_attr( $key ) . '" ' . selected( $key, $meta_field_type, false ) . '>' . esc_html( $field_type ) . '</option>';
									}
									?>
								</select>
							</td>
						</tr>
						<tr class="field_choices" style="display:none;">
							<th scope="row">
								<label for="field_choices"><?php esc_html_e( 'Field Choices', 'custom-meta-fields' ); ?></label>
								<p class="cmf-small">Enter each choice on a new line.<BR>For more control, you may specify both a value and label like this:<BR>red : Red</p>
							</th>
							<td>
								<textarea class="widefat" id="field_choices" name="field_choices" rows="10"><?php echo esc_textarea( implode( "\n", (array) $field_choices ) ); ?></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div id="major-publishing-actions">
					<?php submit_button( $submit_label, 'primary', 'submit', false ); ?>
				</div>                
			</form>
	</div>
</div>
