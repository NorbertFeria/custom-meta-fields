<?php
/**
 * Provide add meta box tab on plugin admin.
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
<div id="add-meta-box" class="tab-content" style="display:none;">
	<?php
	if ( $this->edit_data ) {
		$form_action    = 'update';
		$post_id_input  = '<input type="hidden" name="post_id" value="' . $this->edit_data->ID . '">';
		$meta_box_title = esc_attr( $this->edit_data->post_title );
		$target_post    = get_post_meta( $this->edit_data->ID, 'cmf_target_post', true );
		$submit_label   = __( 'Update Meta Box', 'custom-meta-fields' );
		$form_title     = __( 'Edit Meta Box', 'custom-meta-fields' );
	} else {
		$form_action    = 'save';
		$post_id_input  = '';
		$meta_box_title = '';
		$target_post    = '';
		$submit_label   = __( 'Save Meta Box', 'custom-meta-fields' );
		$form_title     = __( 'Add New Meta Box', 'custom-meta-fields' );
	}
	?>
	<div class="postbox postbox-meta">
		<div class="postbox-header">
			<h2 class="postbox-meta-header"><?php echo esc_html( $form_title ); ?></h2>
			<?php if ( $this->edit_data ) : ?>
				<div class="postbox-meta-header-btns">
					<a href="<?php echo esc_html( admin_url( 'admin.php?page=custom-meta-fields&tab=add-meta-box' ) ); ?>" class="button postbox-meta-header-btn">Add new Meta box</a>
				</div>
			<?php endif; ?>
		</div>
		<form method="post" action="<?php echo esc_html( $admin_url ); ?>&tab=existing-meta-boxes">
			<div class="inside">		
				<?php wp_nonce_field( 'meta_box_form', 'meta_box_nonce' ); ?>
				<input type="hidden" name="action" value="<?php echo esc_html( $form_action ); ?>">
				<input type="hidden" name="context" value="meta_box">
				<?php echo esc_html( $post_id_input ); ?>				
				<table class="form-table">
					<tr>
						<th scope="row"><label for="meta_box_title"><?php esc_html_e( 'Meta box title', 'custom-meta-fields' ); ?></label></th>
						<td><input type="text" name="meta_box_title" id="meta_box_title" class="regular-text" value="<?php echo esc_html( $meta_box_title ); ?>" required></td>
					</tr>
					<tr>
						<th scope="row"><label for="target_post"><?php esc_html_e( 'Target post type', 'custom-meta-fields' ); ?></label></th>
						<td>
							<select name="target_post" id="target_post" class="regular-text">
								<?php
								$post_types = get_post_types( array( 'public' => true ), 'objects' );

								foreach ( $post_types as $box_post_type ) {
									echo '<option value="' . esc_attr( $box_post_type->name ) . '" ' . selected( $box_post_type->name, $target_post, false ) . '>' . esc_html( $box_post_type->label ) . '</option>';
								}
								?>
							</select>
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
