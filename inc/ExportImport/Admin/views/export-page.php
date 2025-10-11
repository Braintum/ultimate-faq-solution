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

	<h1><?php esc_html_e( 'Export / Import page.', 'ufaqsw' ); ?></h1>
	<p>
		<?php
		esc_html_e(
			'Use this tool to export and import your FAQ data between different sites using the Ultimate FAQ Solution plugin.',
			'ufaqsw'
		);
		?>
	</p>

	<p>
		<?php
		esc_html_e(
			'You can choose to export or import only specific data types — such as FAQ Groups, Appearances, FAQ Assistant, AI Integration, or Plugin Settings. The exported file will be generated in JSON format, which can easily be imported on another site running the same plugin.',
			'ufaqsw'
		);
		?>
	</p>

	<div style="margin-top:10px; padding:10px; background:#fff3cd; border-left:4px solid #ff9800;">
		<strong><?php esc_html_e( 'Note:', 'ufaqsw' ); ?></strong>
		<?php
		esc_html_e(
			'Uploaded media such as images, videos, or other attachments linked to your FAQs will not be included in the export file. Only text-based FAQ data, configuration, and appearance settings will be exported. You\'ll need to manually upload media files on the destination site if they\'re used inside your FAQ content.',
			'ufaqsw'
		);
		?>
	</div>

	<h2><?php esc_html_e( 'Export', 'ufaqsw' ); ?></h2>
	<form method="post" action="">
		<p><?php esc_html_e( 'Select the specific data types you want to export. You can choose one or multiple options based on your needs:', 'ufaqsw' ); ?></p>

		<label>
			<input type="checkbox" name="ufs_export_types[]" value="faq_groups">
			<?php esc_html_e( 'FAQ Groups', 'ufaqsw' ); ?>
		</label><br>

		<label>
			<input type="checkbox" name="ufs_export_types[]" value="appearances">
			<?php esc_html_e( 'Appearances', 'ufaqsw' ); ?>
		</label><br>

		<label>
			<input type="checkbox" name="ufs_export_types[]" value="faq_assistant">
			<?php esc_html_e( 'FAQ Assistant', 'ufaqsw' ); ?>
		</label><br>

		<label>
			<input type="checkbox" name="ufs_export_types[]" value="settings">
			<?php esc_html_e( 'Settings', 'ufaqsw' ); ?>
		</label><br>

		<label>
			<input type="checkbox" name="ufs_export_types[]" value="ai_integration">
			<?php esc_html_e( 'AI Integration', 'ufaqsw' ); ?>
		</label><br>
	
		<input type="hidden" name="ufs_action" value="export">
		<?php submit_button( __( 'Export Selected', 'ufaqsw' ) ); ?>
	</form>

	<hr>

	<h2><?php esc_html_e( 'Import', 'ufaqsw' ); ?></h2>
	<p><?php esc_html_e( 'Upload a JSON file previously exported by this plugin. Import will create new FAQ items and update settings.', 'ufaqsw' ); ?></p>

	<form method="post" enctype="multipart/form-data" style="margin-top:12px;">
		<?php wp_nonce_field( 'ufs_import', 'ufs_import_nonce' ); ?>
		<input type="file" name="ufs_import_file" accept=".json" required />
		<?php submit_button( __( 'Upload and Import', 'ufaqsw' ), 'primary', 'submit', false ); ?>
	</form>
</div>