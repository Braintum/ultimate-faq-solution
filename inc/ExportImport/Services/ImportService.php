<?php
/**
 * Import Service for handling FAQ import operations.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Services
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Services;

use Mahedi\UltimateFaqSolution\ExportImport\Managers\ImportManager;
use Mahedi\UltimateFaqSolution\ExportImport\Helpers\FileHelper;

/**
 * Service class for handling FAQ import operations.
 */
class ImportService {

	/**
	 * The import manager instance.
	 *
	 * @var ImportManager|null
	 */
	protected static ?ImportManager $manager = null;

	/**
	 * Set the import manager instance.
	 *
	 * @param ImportManager $m The import manager instance to set.
	 * @return void
	 */
	public static function setManager( ImportManager $m ): void {
		self::$manager = $m;
	}

	/**
	 * Handle file upload and import FAQ data.
	 *
	 * @param array $file The uploaded file data.
	 * @return array The import report or error information.
	 */
	public static function handleUpload( array $file ): array {
		if ( ! current_user_can( 'manage_options' ) ) {
			return array( 'error' => 'permission' );
		}
		$payload = FileHelper::readUploadedJson( $file );
		if ( ! $payload ) {
			return array( 'error' => 'invalid_json' );
		}
		if ( ! self::$manager ) {
			return array( 'error' => 'no_manager' );
		}
		$report = self::$manager->importPayload( $payload );
		return $report;
	}
}
