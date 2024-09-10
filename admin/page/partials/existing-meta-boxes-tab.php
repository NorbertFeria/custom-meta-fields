<?php
/**
 * Provide existing meta boxes tab on plugin admin.
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
<div id="existing-meta-boxes" class="tab-content">
		<h2 class="wp-heading-inline"><?php esc_html_e( 'Meta Boxes', 'custom-meta-fields' ); ?></h2>
		<a href="<?php echo esc_html( admin_url( 'admin.php?page=custom-meta-fields&tab=add-meta-box' ) ); ?>" class="page-title-action">Add new Meta box</a>
		<p>Each meta box can contain multiple meta fields. Assign meta boxes to specific post types.  Use this tab to manage and organize your meta boxes.</p>
		<table class="widefat striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Title', 'custom-meta-fields' ); ?></th>
					<th><?php esc_html_e( 'Target post', 'custom-meta-fields' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'custom-meta-fields' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$meta_boxes = get_posts(
					array(
						'post_type'   => 'cmf_meta_box',
						'numberposts' => -1,
						'post_status' => 'any',
					)
				);
				if ( $meta_boxes ) {
					foreach ( $meta_boxes as $meta_box ) {
						echo '<tr rel="' . esc_attr( $meta_box->ID ) . '">';
						echo '<td>' . esc_html( $meta_box->post_title ) . '</td>';
						$target_post = get_post_meta( $meta_box->ID, 'cmf_target_post', true );
						echo '<td>' . esc_html( $target_post ) . '</td>';
						echo '<td>';

						// Activate/Deactivate Button.
						$status_label = ( 'publish' === $meta_box->post_status ) ? esc_html__( 'Deactivate', 'textdomain' ) : esc_html__( 'Activate', 'textdomain' );
						echo '<form action="' . esc_html( $admin_url ) . '&tab=existing-meta-boxes" method="post" style="display:inline;">';

						wp_nonce_field( 'meta_box_toggle_status', 'meta_box_toggle_status_nonce' );
						echo '<input type="hidden" name="action" value="toggle_status">';
						echo '<input type="hidden" name="context" value="meta_box">';
						echo '<input type="hidden" name="post_id" value="' . esc_attr( $meta_box->ID ) . '">';

						submit_button( $status_label, 'primary', '', false );
						echo '</form> ';

						// Edit Button.
						echo '<form method="post" action="' . esc_html( $admin_url ) . '&tab=add-meta-box" style="display:inline;">';
						echo '<input type="hidden" name="page" value="custom-meta-fields">';

						wp_nonce_field( 'meta_boxesc_html_edit', 'meta_boxesc_html_edit_nonce' );
						echo '<input type="hidden" name="action" value="edit">';
						echo '<input type="hidden" name="context" value="meta_box">';
						echo '<input type="hidden" name="post_id" value="' . esc_attr( $meta_box->ID ) . '">';

						submit_button( esc_html__( 'Edit', 'custom-meta-fields' ), 'button-edit', '', false );
						echo '</form> ';

						// Delete Button.
						echo '<form action="' . esc_html( $admin_url ) . '&tab=existing-meta-boxes" method="post" style="display:inline;">';

						wp_nonce_field( 'meta_box_delete', 'meta_box_delete_nonce' );
						echo '<input type="hidden" name="action" value="delete">';
						echo '<input type="hidden" name="context" value="meta_box">';
						echo '<input type="hidden" name="post_id" value="' . esc_attr( $meta_box->ID ) . '">';

						submit_button( esc_html__( 'Delete', 'custom-meta-fields' ), 'button-delete', '', false );
						echo '</form>';
						echo '</td>';
						echo '</tr>';
					}
				} else {
					echo '<tr><td colspan="2">' . esc_html__( 'No meta boxes found.', 'custom-meta-fields' ) . '</td></tr>';
				}
				?>
				<tfoot>
					<th><?php esc_html_e( 'Title', 'custom-meta-fields' ); ?></th>
					<th><?php esc_html_e( 'Target post', 'custom-meta-fields' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'custom-meta-fields' ); ?></th>
				</tfoot>
			</tbody>
		</table>
	</div>
