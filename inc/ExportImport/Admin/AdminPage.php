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
	}

	/**
	 * Add the Export/Import submenu page under the FAQ menu.
	 *
	 * @return void
	 */
	public static function add_menu(): void {
		add_submenu_page(
			'edit.php?post_type=ufaqsw',
			__( 'Export/Import', 'ultimate-faq-solution' ),
			__( 'Export/Import', 'ultimate-faq-solution' ),
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
				$res    = ImportService::handleUpload( $_FILES['ufs_import_file'] ); // phpcs:ignore
				self::$report = $res;
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
}
