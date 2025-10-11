<?php
/**
 * Export Service for handling FAQ export operations.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Services
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Services;

use Mahedi\UltimateFaqSolution\ExportImport\Managers\ExportManager;
use Mahedi\UltimateFaqSolution\ExportImport\Helpers\FileHelper;

/**
 * Service class for handling FAQ export operations.
 */
class ExportService {

	/**
	 * The export manager instance.
	 *
	 * @var ExportManager|null
	 */
	protected static ?ExportManager $manager = null;

	/**
	 * Set the export manager instance.
	 *
	 * @param ExportManager $m The export manager instance to set.
	 * @return void
	 */
	public static function setManager( ExportManager $m ): void {
		self::$manager = $m;
	}

	/**
	 * Export FAQ data and trigger JSON file download.
	 *
	 * @param array $selectedTypes The types of data to export.
	 * @return void
	 */
	public static function exportDownload( array $selectedTypes ): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Insufficient permission' );
		}
		if ( ! self::$manager ) {
			wp_die( 'Export manager not initialized' );
		}
		$json = self::$manager->exportToJson( $selectedTypes );

		FileHelper::downloadJsonResponse( $json, 'ufs-export-' . date( 'Ymd-His' ) . '.json' ); // phpcs:ignore
	}
}
