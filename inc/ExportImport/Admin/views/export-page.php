<?php
/**
 * Export page view for Ultimate FAQ Solution plugin
 *
 * This file contains the HTML form for exporting FAQ data including
 * FAQ groups, items, appearances, and settings.
 *
 * @package Ultimate_FAQ_Solution
 * @since 1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">

	<?php if ( isset( $report ) && is_array( $report ) ) : ?>
		<div style="background:#fff;padding:10px;border:1px solid #ccd;max-width:800px;">
			<h3><?php esc_html_e( 'Import Report', 'ufaqsw' ); ?></h3>
			<pre style="white-space:pre-wrap;"><?php echo esc_html( print_r( $report, true ) ); ?></pre>
		</div>
	<?php endif; ?>

	<h1><?php esc_html_e( 'Export FAQs', 'ufaqsw' ); ?></h1>
	<form method="post" action="">
		<p><?php esc_html_e( 'Select what you want to export:', 'ufaqsw' ); ?></p>

		<label>
			<input type="checkbox" name="ufs_export_types[]" value="faq_groups">
			<?php esc_html_e( 'FAQ Groups', 'ufaqsw' ); ?>
		</label><br>

		<label>
			<input type="checkbox" name="ufs_export_types[]" value="appearances">
			<?php esc_html_e( 'Appearances', 'ufaqsw' ); ?>
		</label><br>
	
		<input type="hidden" name="ufs_action" value="export">
		<?php submit_button( __( 'Export Selected', 'ufaqsw' ) ); ?>
	</form>

	<hr>

	<h2><?php esc_html_e( 'Import', 'ufaqsw' ); ?></h2>
	<p><?php esc_html_e( 'Upload a JSON previously exported by this plugin. Import will create posts and options.', 'ufaqsw' ); ?></p>

	<form method="post" enctype="multipart/form-data" style="margin-top:12px;">
		<?php wp_nonce_field( 'ufs_import', 'ufs_import_nonce' ); ?>
		<input type="file" name="ufs_import_file" accept=".json" required />
		<?php submit_button( __( 'Upload and Import', 'ufaqsw' ), 'primary', 'submit', false ); ?>
	</form>
</div>