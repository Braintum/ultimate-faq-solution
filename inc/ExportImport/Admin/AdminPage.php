<?php
/**
 * Admin Page for Export/Import functionality.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Admin
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Admin;

use Mahedi\UltimateFaqSolution\ExportImport\Services\ExportService;
use Mahedi\UltimateFaqSolution\ExportImport\Services\ImportService;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Page for Export/Import functionality.
 */
class AdminPage {

	/**
	 * Report data to show after import/export actions.
	 *
	 * @var array
	 */
	private static $report = array();

	/**
	 * Initialize the admin page by hooking into WordPress actions.
	 *
	 * @return void
	 */
	public static function init(): void {

		// Hook into the admin menu.
		add_action( 'admin_menu', array( self::class, 'add_menu' ) );

		// Hook into admin_init to handle form submissions.
		add_action( 'admin_init', array( self::class, 'handle_form_submission' ) );

		// Hook into admin_notices to display reports.
		add_action( 'admin_notices', array( self::class, 'display_admin_notices' ) );
	}

	/**
	 * Add the Export/Import submenu page under the FAQ menu.
	 *
	 * @return void
	 */
	public static function add_menu(): void {
		add_submenu_page(
			'edit.php?post_type=ufaqsw',
			__( 'Export/Import', 'ufaqsw' ),
			__( 'Export/Import', 'ufaqsw' ),
			'manage_options',
			'ufs-export-import',
			array( self::class, 'render' )
		);
	}

	/**
	 * Handle form submissions for export and import actions.
	 *
	 * @return void
	 */
	public static function handle_form_submission(): void {
		// This function is intentionally left blank as form handling is done in render().
		if ( isset( $_POST['ufs_action'] ) && 'export' === $_POST['ufs_action'] && isset( $_POST['ufs_export_types'] ) && is_array( $_POST['ufs_export_types'] ) ) { // phpcs:ignore
			$selectedTypes = array_map( 'sanitize_text_field', $_POST['ufs_export_types'] ); // phpcs:ignore
			try {
				ExportService::exportDownload( $selectedTypes );
				exit; // Exit with download headers.
			} catch ( \Exception $e ) {
				// Store error in session or handle differently since we're in static context.
				self::$report = array( 'error' => $e->getMessage() );
			}
		}

		// Handle import form submission.
		if ( isset( $_POST['ufs_import_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ufs_import_nonce'] ) ), 'ufs_import' ) ) {
			if ( ! empty( $_FILES['ufs_import_file'] ) ) {
				// Check MIME type for security.
				$allowed_types = array( 'application/json' );
				$file_type     = isset( $_FILES['ufs_import_file']['type'] ) ? sanitize_text_field( $_FILES['ufs_import_file']['type'] ) : '';

				if ( ! in_array( $file_type, $allowed_types, true ) ) {
					self::$report = array( 'error' => esc_html__( 'Invalid file type. Only JSON files are allowed.', 'ufaqsw' ) );
				} else {
					$res = ImportService::handleUpload( $_FILES['ufs_import_file'] ); // phpcs:ignore
					self::$report = $res;
				}
			} else {
				self::$report = array( 'error' => 'no_file' );
			}
		}
	}

	/**
	 * Render the Export/Import admin page.
	 *
	 * @return void
	 */
	public static function render(): void {

		if ( ! empty( self::$report ) ) {
			$report = self::$report;
		}
		include UFAQSW__PLUGIN_DIR . 'inc/ExportImport/Admin/views/export-page.php';
	}

	/**
	 * Display admin notices based on the report data.
	 *
	 * @return void
	 */
	public static function display_admin_notices(): void {
		// Only show notices on the export/import page.
		$screen = get_current_screen();
		if ( ! isset( $screen->id ) || 'ufaqsw_page_ufs-export-import' !== $screen->id ) {
			return;
		}

		if ( empty( self::$report ) ) {
			return;
		}

		$report = self::$report;

		// Handle error messages.
		if ( isset( $report['error'] ) ) {
			$error_message = '';
			switch ( $report['error'] ) {
				case 'no_file':
					$error_message = esc_html__( 'Please select a file to import.', 'ufaqsw' );
					break;
				case 'Invalid file type. Only JSON files are allowed.':
					$error_message = esc_html__( 'Invalid file type. Only JSON files are allowed.', 'ufaqsw' );
					break;
				default:
					$error_message = esc_html( $report['error'] );
					break;
			}
			printf(
				'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
				esc_html( $error_message )
			);
		}

		// Handle success messages.
		if ( isset( $report['results'] ) && $report['results'] ) {
			$success_message = '';

			foreach ( $report['results'] as $key => $value ) {
				// translators: %1$s is the import result key (like 'faqs', 'categories'), %2$s is the number/count value.
				$success_message .= sprintf( esc_html__( '%1$s: %2$s', 'ufaqsw' ), ucfirst( str_replace( '_', ' ', $key ) ), $value ) . '<br>';
			}

			printf(
				'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
				$success_message // phpcs:ignore
			);
		}

		// Clear the report after displaying.
		self::$report = array();
	}
}
