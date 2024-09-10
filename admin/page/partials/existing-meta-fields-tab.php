<?php
/**
 * Provide existing meta fields tab on plugin admin.
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
<div id="existing-meta-fields" class="tab-content" style="display:none;">
		<h2 class="wp-heading-inline"><?php esc_html_e( 'Meta Fields', 'custom-meta-fields' ); ?></h2>
		<a href="<?php echo esc_html( admin_url( 'admin.php?page=custom-meta-fields&tab=add-meta-field' ) ); ?>" class="page-title-action">Add new meta field</a>
		<p>Meta fields allow you to add custom data fields to your posts and custom post types. Each meta field is assigned to a meta box. Use this tab to manage your meta fields.</p>
		<table class="wp-list-table widefat fixed striped table-view-list">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Field label', 'custom-meta-fields' ); ?></th>
					<th><?php esc_html_e( 'Field Name', 'custom-meta-fields' ); ?></th>
					<th><?php esc_html_e( 'Meta box', 'custom-meta-fields' ); ?></th>
					<th><?php esc_html_e( 'Field type', 'custom-meta-fields' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'custom-meta-fields' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$meta_fields = get_posts(
					array(
						'post_type'   => 'cmf_meta_field',
						'numberposts' => -1,
						'post_status' => 'any',
					)
				);

				if ( $meta_fields ) {
					foreach ( $meta_fields as $meta_field ) {
						$parent_meta_box_id    = $meta_field->post_parent;
						$parent_meta_box       = get_post( $parent_meta_box_id );
						$parent_meta_box_title = $parent_meta_box ? $parent_meta_box->post_title : esc_html__( 'No Parent', 'custom-meta-fields' );
						?>
						<tr>
							<td><?php echo esc_html( $meta_field->post_title ); ?></td>
							<td><?php echo esc_html( $meta_field->post_name ); ?></td>
							<td><?php echo esc_html( $parent_meta_box_title ); ?></td>
							<td><?php echo esc_html( get_post_meta( $meta_field->ID, 'cmf_field_type', true ) ); ?></td>
							<td>
								<?php
									// Activate/Deactivate Button.
									$status_label = ( 'publish' === $meta_field->post_status ) ? esc_html__( 'Deactivate', 'custom-meta-fields' ) : __( 'Activate', 'custom-meta-fields' );
									echo '<form method="post" action="' . esc_attr( $admin_url ) . '&tab=existing-meta-fields" style="display:inline;">';

									wp_nonce_field( 'meta_field_toggle_status', 'meta_field_toggle_status_nonce' );

									echo '<input type="hidden" name="action" value="toggle_status">';
									echo '<input type="hidden" name="context" value="meta_field">';
									echo '<input type="hidden" name="post_id" value="' . esc_attr( $meta_field->ID ) . '">';

									submit_button( $status_label, 'primary', '', false );
									echo '</form> ';

									// Edit Button.
									echo '<form method="post" action="' . esc_attr( $admin_url ) . '&tab=add-meta-field" style="display:inline;">';

									wp_nonce_field( 'meta_field_edit', 'meta_field_edit_nonce' );
									echo '<input type="hidden" name="action" value="edit">';
									echo '<input type="hidden" name="context" value="meta_field">';
									echo '<input type="hidden" name="post_id" value="' . esc_attr( $meta_field->ID ) . '">';

									submit_button( esc_html__( 'Edit', 'custom-meta-fields' ), 'button-edit', '', false );
									echo '</form> ';

									// Delete Button.
									echo '<form method="post" action="' . esc_attr( $admin_url ) . '&tab=existing-meta-fields" style="display:inline;">';

									wp_nonce_field( 'meta_field_delete', 'meta_field_delete_nonce' );
									echo '<input type="hidden" name="action" value="delete">';
									echo '<input type="hidden" name="context" value="meta_field">';
									echo '<input type="hidden" name="post_id" value="' . esc_attr( $meta_field->ID ) . '">';

									submit_button( esc_html__( 'Delete', 'custom-meta-fields' ), 'button-delete', '', false );

									echo '</form>';
									echo '</td>';
									echo '</tr>';
					}
				} else {
					?>
					<tr>
						<td colspan="4"><?php esc_html_e( 'No Meta Fields found.', 'custom-meta-fields' ); ?></td>
					</tr>
					<?php
				}
				?>
				<tfoot>
				<tr>
					<th><?php esc_html_e( 'Field label', 'custom-meta-fields' ); ?></th>
					<th><?php esc_html_e( 'Field Name', 'custom-meta-fields' ); ?></th>
					<th><?php esc_html_e( 'Meta box', 'custom-meta-fields' ); ?></th>
					<th><?php esc_html_e( 'Field type', 'custom-meta-fields' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'custom-meta-fields' ); ?></th>
				</tr>
			</tfoot>
			</tbody>
		</table>
	</div>
